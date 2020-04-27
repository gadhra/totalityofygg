<?php
    /**
     * Main Router
     */
    import([ 'flight' ]);
    
    Flight::set( 'flight.log_errors', true );
    
    Flight::map('error', function( $ex ){
        //if( ENVIRONMENT == 'DEV' ) {
            if(! empty( $ex->xdebug_message ) ) {
                echo '<pre>' . $ex->xdebug_message . '</pre>';
            } else {
                echo '<h2>' . $ex->getMessage() . ' ' . $ex->getFile() . ':' . $ex->getLine() . '</h2>';
                echo '<pre>' . $ex->getTraceAsString() . '</pre>';
                exit;
            }
        //} else {
          //  syslog( LOG_NOTICE, $ex->getTraceAsString() );
        //}
    });


    Flight::route( 'GET /g/@gen(/@limit)', function( $gen, $limit ) {
        Flight::set( 'gen', $gen );
	if( is_null( $limit ) ) {
	    $limit = 20;
	}
	Flight::set( 'limit', intval( $limit ) ); 
        if(! presenter( 'generator', [] ) ) {
                return true;
        }
    });

    Flight::route( 'GET /bin/input', function() {
        include_once( sprintf( '%s%s/%s.php', ABSPATH, 'bin', 'input') );
	return true;
    });
 
    Flight::route( 'GET /@name(/@id(/@spell_id))', function( $name, $id, $spell_id ) {
        Flight::set( 'method', 'GET' );
        $data = Flight::request()->query;
        
        $params = [];
        foreach( $data as $k=>$v ) {
            $params[filter_var($k,FILTER_SANITIZE_STRING)] = filter_var($v,FILTER_SANITIZE_STRING);
        }
        
        /**
          * Override the id if passed RESTfully
          */
        if(! empty( $id ) ) {
            $params['id'] = filter_var($id,FILTER_SANITIZE_STRING);
        }

	/**
          *  Bell, Book, and Candle logic
	  */
	if( $name === 'bbc' && ! empty( $id ) ) {
		$name = 'bbc/' . $id;
		if( $spell_id ) {
			$params['id'] = $spell_id;
		}
	}


        if(! presenter( $name, $params ) ) {
            return true;
        }
    });
    
    Flight::route( 'POST /@name(/@node)', function( $name, $node ) {
        Flight::set( 'method', 'POST' );
        $data = Flight::request()->data;

        $params = [];
        foreach( $data as $k=>$v ) {
            if(! is_array( $v ) ) {
                $params[filter_var($k,FILTER_SANITIZE_STRING)] = makeQuote( filter_var($v,FILTER_SANITIZE_STRING) );
            } else {
                $params[filter_var($k,FILTER_SANITIZE_STRING)] = filter_var_array($v,FILTER_SANITIZE_STRING);
            }
        }

	if(! empty( $node ) ) {
		$name = sprintf( '%s/%s', $name, $node );
	}
        
        if(! presenter( $name, $params ) ) {
            return true;
        }
    });

    Flight::route( 'PUT /@name/@id', function( $name, $id ) {
        Flight::set( 'method', 'PUT' );
        $data = Flight::request()->getBody();
        
        $params = [];
        foreach( $data as $k=>$v ) {
            $params[filter_var($k,FILTER_SANITIZE_STRING)] = filter_var($v,FILTER_SANITIZE_STRING);    
        }
        
        if(! presenter( $name, $params ) ) { 
            return true;
        }
    });
    
    Flight::route( 'DELETE /@name/@id', function( $name, $id ) {
        Flight::set( 'method', 'DELETE' );
        $data = Flight::request()->getBody();
        
        $params = [];
        foreach( $data as $k=>$v ) {
            $params[filter_var($k,FILTER_SANITIZE_STRING)] = filter_var($v,FILTER_SANITIZE_STRING);    
        }

        /**
          * Override the id if passed RESTfully
          */
        if(! empty( $id ) ) {
            $params['id'] = filter_var($id,FILTER_SANITIZE_STRING);
        }        
             
        if(! presenter( $name, $params ) ) {
            return true;
        }
    });


    Flight::route( '*', function() {
        presenter( 'main' );
    });
    
    Flight::start();
