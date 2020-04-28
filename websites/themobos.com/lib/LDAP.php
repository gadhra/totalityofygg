<?php

/**
 * Exception handlers
 */
class LdapException extends Exception {
    public function __construct( $message, $code = 0, Exception $previous = null ) {
        parent::__construct( $message, $code, $previous );
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}

class LdapError {
    private $conn;

    public function __construct( $conn = null ) {
        if(! $conn ) {
            throw new LdapException( 'Unable to connect to LDAP server' );
        }
        $this->conn = $conn;
    }

    public function emit( $extended = null ) {
        if( $extended ) {
            if( ldap_get_option( $this->conn, LDAP_OPT_DIAGNOSTIC_MESSAGE, $er ) ) {
                ldap_get_option( $this->conn, LDAP_OPT_ERROR_NUMBER, $code );
                ldap_get_option( $this->conn, LDAP_OPT_ERROR_STRING, $msg );
                throw new LdapException( $msg, $code );
            } else {
                throw new LdapException( 'No additional information available', 0 );
            }
        } else {
            $errno = ldap_errno( $this->conn );
            throw new LdapException( ldap_err2str( $errno ), $errno );
        }
    }
}

/**
 * LDAP class
 */
class LDAP {
    private $conn, $bind;
    private $password;
    private $user = null;
    private $err;
    
    function __construct() {
        // we need to do this to get exceptions working correctly
        error_reporting(0);
        if( constant( 'DEBUG' ) && DEBUG === true ) {
            ldap_set_option( NULL, LDAP_OPT_DEBUG_LEVEL, 7 );
            error_reporting(1);
        }
        
        $this->conn = ldap_connect( LDAP_SERVER, LDAP_PORT );
        if(! is_resource( $this->conn ) ) {
            throw new LdapException( 'LDAP connection not a resource' );
        }
       
        $this->err = new LdapError( $this->conn );
        ldap_set_option( $this->conn, LDAP_OPT_PROTOCOL_VERSION, 3 );
        if( LDAP_TLS === true ) {
            ldap_start_tls( $this->conn );
        }
        
        try {
            $this->bind = ldap_bind( $this->conn, LDAP_RDN, LDAP_PW );
            if(! $this->bind ) {
                $this->err->emit( true );
            }
        } catch( LdapException $e ) {
            throw $e;
        }
    }

    public function create() {
        throw new LdapException( 'foobar', 500 );
    }

    public function reset_pw( $userObj ) {
        $vars = get_object_vars( $userObj );
        $required = [ 'isHashSynced'=>1, 'username'=>1, 'hash'=>1];
        if( count( array_diff_key( $required, $vars ) ) !== 0 ) {
            throw new LdapException( 'Malformed Request - keys missing' );
        }

        if( $vars['isHashSynced'] != 0 ) {
            throw new LdapException( 'No sync permitted' );
        }
        
        try {
            $filter = sprintf( "(uid=%s)", $vars['username'] );
            $ldap_user = $this->search( $filter );
            $pw = [];
            $pw['userpassword'] = $vars['hash'];
            if(! ldap_mod_replace( $this->conn, $ldap_user['dn'], $pw ) ) {
                $this->err->emit();
            }
            return true;
        } catch( LdapException $e ) {
            throw $e;
        }
    }

    public function search( $filter ) {
        $results = ldap_search( $this->conn, LDAP_DN, $filter );
        $entries = ldap_get_entries( $this->conn, $results );

        if(! is_array( $entries ) || ! array_key_exists( 'count', $entries ) ) {
            throw new LdapException( 'Malformed response' );
        }

        $cnt = $entries['count'];

        if( $cnt == 0 ) {
            throw new LdapException( 'User not found', 404 );
        }

        if( $cnt > 1 ) {
            throw new LdapException( 'Multiple entries found' );
        }

        $user = [];
        // the keys are mixed as associative and indexed arrays
        // just cleaning them out for sanity
        foreach( $entries[0] as $key=>$val ) {
            if( is_numeric( $key ) ) {
                continue;
            }

            // Skip kerberos, the binary encryption messes this up in the terminal
            // plus - really no need to store an encrypted pw in memory!
            if( strpos( $key, 'krb' ) !== false ) {
                continue;
            }

            $user[$key] = $val;
        }

        return $user;
    }
}

/**
 * Function I used to sync a password from MySQL down to a 
 * an LDAP instance.  Takes a user object (see User.php)
 */
function sync( string $uid, object $user ) {
    $needCreate = false;
    $ldap = new LDAP;

    // find them in ldap
    try {
        $search = sprintf( "(uid=%s)", $uid);
        $ldap->search( $search );
    } catch( LdapException $e ) {
        if( $e->getCode() == 404 ) {
            $needCreate = true;
        } else {
            throw $e;
        }
    }

     // break out for a second and handle account creation
     // and rerun if necessary
     if( $needCreate ) {
         try {
             $ldap->create( $user );
             sync( $uid );
         } catch( LdapException $e ) {
             throw $e;
         }
     }

    // sync the password
    try {
        $ldap->reset_pw( $user );
    } catch( LdapException $e ) {
        throw $e;
    }
}
?>
