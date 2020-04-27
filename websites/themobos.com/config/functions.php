<?php
    
    /**
      * GLOBAL function definitions, included from load
      */
      
      
    
   /**
     * include presenter file
     * @param  string $fname  name of the file to route to
     * @param  array  $params any params you want to pass to the template
     * @return bool         include the file, return true if it works, false otherwise
     */
    function presenter( $fname, $params = [] ) {
        $path = sprintf( '%s%s/%s.php', ABSPATH, 'presenter', $fname );
        if( file_exists( $path ) ) {
            $params = toArray( $params );
            extract( $params );
            include_once( $path );
            return true;
        }
        return false;
    }

    function makeQuote( $input ) {
		return preg_replace_callback("/(&#[0-9]+;)/", function($m) { return mb_convert_encoding($m[1], "UTF-8", "HTML-ENTITIES"); }, $input );
	}
	



    function getDiceChain( $num = -1 ) {
        $num = ( int ) $num;

        $diceChain = [
        'd2',
        'd3',
        'd4',
        'd5',
        'd6',
        'd7',
        'd8',
        'd10',
        'd12',
        'd14',
        'd16',
        'd20',
        'd24',
        'd30'
        ];

        if( $num <= -1 || $num > count( $diceChain ) ) {
            return $diceChain;
        }

        return $diceChain[$num];
    }   
    
    function roll20( $type = 'd20' ) {
        $type = preg_replace( '/\s+/', '', $type );
        preg_match( '/.*?(([0-9]*)d([0-9]*)).*/', $type, $matches );
        if( empty( $matches ) ) {
            return $type;
        }

        $dicestr = $matches[1];
        $min = ( int ) $matches[2];
        $max = ( int ) $matches[3];


        if( empty( $min ) ) {
            $min = 1;
        }
        $max = $max * $min;
        $roll = mt_rand( $min, $max );
        $roll_modified = str_replace( $dicestr, $roll, $type );
        return eval( "return $roll_modified;" );


    }

    /**
      * taking advantage of PHP object handling with this hack
      * and I feel a bit bad about it
      */
    function toArray( $obj ) {
        if( is_array( $obj ) ) {
            return $obj;
        }
        
        if( is_object( $obj ) ) {
            return json_decode( json_encode( $obj ), true );
        }
        
        return [];
    }


    //@todo let all of this get handled by composer
    function import( $libs = [] ) {
        if( empty( $libs ) ) {
            return;
        }
        
        if( is_string( $libs ) ) {
            $tmp = $libs;
            $libs = [];
            $libs[] = $tmp;
            unset( $tmp );
        }

        foreach( $libs as $lib ) {
            $path = sprintf( '%s%s/%s.php', ABSPATH, 'lib', $lib );
            //check local first
            if( file_exists( $path ) ) {
                require_once( $path );
            }
        }

        // load up the composer autoloader
        require_once( sprintf( '%s%s/vendor/autoload.php', ABSPATH, 'lib' ) );
        return;
    }
