<?php

     class Auth {

        private $conn;
        
        public function __construct() {
            import( 'DB' );
            import('Session');        
            $this->conn = new MySQL;
        }
        
        public function check() {
            if( empty( $_SESSION['token'] ) ) {
                return false;
            }
            
            $token = $_SESSION['token'];
            $sql = 'SELECT 1 FROM auth WHERE token = ?';
            $auth = $this->conn->fetchOne( $sql, [ $token ] );
            if( empty( $auth ) ) {
                return false;
            }
            
            return true;
        }
        
        public function logout() {
            if( empty( $_SESSION['token'] ) ) {
                return;
            }
            
            $token = $_SESSION['token'];
            $sql = 'SELECT id FROM auth WHERE token = ?';
            $id = $this->fetchOne( $sql, [ $token ] );
            if(! empty( $id ) ) {
                $this->detoke( $id );
            }     
            unset( $_SESSION['token'] );
            return;          
        }
        
        public function login( $u, $p ) {
            $sql = 'SELECT id, password FROM auth WHERE username = ? OR email = ?';
            $pw = $this->conn->fetchRow( $sql, [ $u, $u ] );
            if( empty( $pw ) ) {
                return false;
            }

            $this->detoke( $pw['id'] );
            
            if(! password_verify( $p, $pw['password'] ) ) {
                return false;
            }
            
            return $this->toke( $pw['id'] );
        }
        
        private function toke( $id ) {
            $token = password_hash( uniqid(), PASSWORD_DEFAULT );
            $_SESSION['token'] = $token;
            $sql = 'UPDATE auth SET token = ? WHERE id = ?';
            $this->conn->run( $sql, [$token, $id] );
            return true;
        }
        
        private function detoke( $id ) {
            unset( $_SESSION['token'] );
            $sql = 'UPDATE auth SET token = ? WHERE id = ?';
            $this->conn->run( null, $id );
            return true;            
        }
        
        public function create( $data = [] ) {
            if( empty( $data ) ) {
                //@todo throw exception
                return false;
            }
            
            //@todo add validation
            $username = $data['u'];
            $email = $data['e'];
            $pass = password_hash( $data['pw'], PASSWORD_DEFAULT );
            
        }
        
        public function forgot() {
            
        }
        
    }
