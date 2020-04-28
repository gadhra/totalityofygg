<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

import(['LDAP', 'DB', 'PHPMailer', 'Template']);

/**
 * Exception handlers
 */
class UserException extends Exception {
    public function __construct( $message, $code = 0, Exception $previous = null ) {
        parent::__construct( $message, $code, $previous );
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}

/**
 *  User class
 */
class User {
    private $db;
    private $password;
    private $user = null;
    private $orig = null;
    private $err;
    private $isModified = false;
    private $keycache = [];
    

    function __construct() {
        try {
            $this->db = new MySQL;
        } catch( Exception $e ) {
            throw $e;
        }
    }


    /**
     * Login functionality
     *
     * @param string $email     email address
     * @param string $password  password
     * @return bool
     */
    public function login( $email, $password ) {
        try {
            $sql = 'SELECT hash FROM users WHERE email = ?';
            $rows = $this->db->fetch( $sql, $email );
        } catch( MyDatabaseException $e ) {
            throw $e;
        }

        // user not found
        if( empty( $rows ) ) {
            return false;
        }

        $hash = $rows[0]['hash'];
        return password_verify( $password, $hash );
    }

    /**
     * Magic setter, get the basic vars into a keycache for checking
     * Otherwise the setter will overwrite user attributes
     *
     * @param string $att    attribute name
     * @param string $val    attribute valu
     */
    public function __set( $att, $val ) {
        if( empty( $this->cache ) ) {
            $this->keycache = array_keys( get_object_vars( $this ) );
        }

        if( array_key_exists( $att, $this->keycache ) ) {
            $this->$att = $val;
        } else {
            $this->user->$att = $val;
        }
    }

    /**
     * Change a user's password
     *
     * @param int $id  user id
     * @param string $pass1  password
     * @param string $pass2  password confirmation
     */
    public function change_pw( $id, $pass1, $pass2 ) {
        try {
            $this->validate_pw( $pass1, $pass2 );
            $this->load( $id );
            $this->hash = password_hash( $pass1 );
            $this->isHashSynced = 0;
            $this->save();
            $this->sync( $id );
        } catch( UserException $e ) {
            throw $e;
        }
    }

    /**
     * Forgot password functionality
     *
     * @param $email  the user's email address
     */
    public function forgot_pw( string $email ) {
        $email = filter_var( $email, FILTER_SANITIZE_EMAIL );
        $valid = filter_var( $email, FILTER_VALIDATE_EMAIL );

        if(! $valid ) {
            throw new UserException( 'invalid email',  403 );
        }

        try {
            $sql = 'SELECT 1 FROM users WHERE email = ?';
            $rows = $this->db->fetch( $sql, [ $email ] );
        } catch( MyDatabaseException $e ) {
            throw $e;
        }

        if( count( $rows ) == 0 ) {
            throw new UserException( 'no account', 404 );
        }

        try {
            $this->load( $email );
            $hash = md5( time() . $this->generate_str() );     
            $this->validation_hash = $hash;
            $this->save();   

            // we're good, send them the email
            $vars = [];
            $vars['subject'] = '';
            $vars['to'] = $email;
            $vars['hash'] = $hash;
            $vars['from'] = sprintf( 'no-reply@%s', YOUR_DOMAIN);         
            $this->send( 'forgot_pw', $vars );

            // return an obfuscated email address
            return preg_replace( "/(?!^).(?=[^@]+@)/", "*", $vars['to'] );
        } catch( UserException $e ) {
            throw $e;
        }
    }

    /**
     * Confirm a user's validation hash is correct
     *
     * @param string $hash  the validation hash 
     * @return email address associated with the hash
     */
    public function confirm( string $hash ) {
        $sql = 'SELECT email FROM users WHERE validation = ?';
        $rows = $this->db->fetch( $sql, [ $hash ] );
        if( count( $rows ) == 0 ) {
            throw new UserException( 'invalid hash', 500 );
        }
        return $rows[0]['email'];
    }
    
    /**
     * Password validation
     *
     * @param  $pass1   the user's password they've entered
     * @param  $pass2   the user's confirmation password
     */
    public function validate_pw( $pass1, $pass2 ) {      
        if( $pass1 !== $pass2 ) {
            throw new UserException( 'Passwords do not match', 406 );
        }

        if( strlen( $pass1 ) < 8 ) {
            throw new UserException( 'Password must be at least 8 characters', 411 );
        }
    }

    /**
     * Verify and clean up the hash
     *
     * @param string $email  the email address of the account
     * @param string hash  the validation hash
     * @return int $id  the id of the user
     */
    public function verify_clean_hash( string $email, string $hash ) {
        $sql = 'SELECT id FROM users WHERE email = ? AND validation = ?';
        $rows = $this->db->fetch( $sql, [ $email, $hash ]  );

        // cleanup immediately when someone clicks on the link
        $sql = 'UPDATE users SET validation_hash = NULL WHERE validation = ?';
        $this->db->run( $sql, [ $hash ] );
        
        // handle validation
        if( count( $rows ) != 1 ) {
            throw new UserException( 'invalid hash', 500 );
        }

        return $rows[0]['id'];
    }

    /**
     * Save the user object
     */
    public function save() {
        if( empty( $this->user ) ) {
            return;
        }

        $diff = array_diff_assoc( get_object_vars( $this->user ), get_object_vars( $this->orig ) );
        $cnt = count( $diff );

        if( $diff == 0 ) {
            return;
        }
    
        // add modification timestamp and rekey using the same logic
        $this->isModified = true;
        $this->modified = date( 'Y-m-d H:i:s' );

        $diff = array_diff_assoc( get_object_vars( $this->user ), get_object_vars( $this->orig ) );
        $cnt = count( $diff );
 
        $keys = array_keys( $diff );
        $vals = array_values( $diff );

        for( $i=0; $i<$cnt; $i++ ) {
            $fields[] = sprintf( "%s=?", $keys[$i] );
        }

        // build and run sql update
        $sql = 'UPDATE users SET ' . implode( ',', $fields ) . ' WHERE id = ' . $this->user->id;
        $this->db->run( $sql, $vals );
        // reload the user
        $this->load( $this->user->id );
        return true;
    }

    /**
     * Send an email 
     *
     * @param string $template  the name of the Twig template we're sending
     * @param array  $vars array of variables to pass to the template
     */
    private function send( $template, $vars ) {
        $mail = new PHPMailer( true );
        $tpl = new Template();
        $body = $tpl->twig->render( "mail/$template.html", $vars );

        try {
            $mail->SMTPDebug = 2;
            $mail->isSMTP();
            $mail->Host = 'localhost';
            
            $mail->setFrom( sprintf( 'no-reply@%s', YOUR_DOMAIN ) );
            $mail->isHTML( true );
            $mail->Subject = $vars['subject'];
            $mail->Body = $body;
            $mail->send();
        } catch (Exception $e ) {
            throw $mail->ErrorInfo;
        }
    }

    /**
     * Load a user
     *
     * @param $identifier  can be an email or user_id
     */
    private function load( $identifier ) {
        // imperfect object cache check
        if(! empty( $this->id ) && $identifier == $this->id ) {
            return;
        }

        if( filter_var( $identifier, FILTER_VALIDATE_EMAIL ) ) {
            $sql = 'SELECT * FROM users WHERE email = ?';
        } elseif( is_int( $identifier ) ) {
            $sql = 'SELECT * FROM users WHERE id = ?';
        } else {
            throw new UserException( 'invalid request', 500 );
        }

        try {
            $rows = $this->db->fetch( $sql, [ $identifier ] );
        } catch( Exception $e ) {
            throw $e;
        }
        
        if( count( $rows ) == 0 ) {
            throw new UserException( 'no account', 404 );
        }
        
        $this->orig = ( object ) $rows[0];
        $this->user = ( object ) $rows[0];
        return;
    }

    /**
     * Generate a string for encryption
    */
    private function generate_str(int $len=32) {
        return str_shuffle( bin2hex( random_bytes($len) ) ); 
    }

}
?>

