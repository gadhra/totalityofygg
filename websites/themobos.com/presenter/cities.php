<?php
    /**
     * Metropolis::
     * 
     * @author gadhra <iam@stefans.computer>
     * @package PHP-Barebones
    */
    
    $method = Flight::get( 'method' );
    import( 'Auth' );
    

    $auth = new Auth;    
    if(! $auth->check() ) {
        Flight::redirect( '/login' );
        exit(0);
    }
    
    $messages = [ 'success'=>[], 'error'=> [] ];
    $view = 'cities.html';
    $cityId = empty( $params['cityId'] ) ? 0 : ( int ) $params['cityId'];
    $city = null;
    

    
    //load all the cities
    import( 'DB' );
    $db = new MySQL;
    $sql = 'SELECT * FROM cities ORDER BY level';
    $cities = $db->fetchAll( $sql, [], true );
    
    if( $method === 'POST' && ! empty( $action ) && $action = 'save' ) {
        // massage landmarks
        $landmarks = explode( "\n", $landmarks );
        $landmarks_json = [];
        foreach( $landmarks as $landmark ) {
            $x = explode( ',', $landmark );
            if( count($x) != 3 ) {
                $messages['error'][] = 'You messed up the landmarks, not saving';
                goto view;
            }
            $name = trim( $x[0] );
            $chance = trim( $x[1] );
            $die =  trim( $x[2] );
            $landmarks_json[$name] = [ 'chance'=>$chance, 'num'=>$die ];
        }
                        
        $sql = 'UPDATE cities SET population = ?, xp = ?, neighborhoods = ?, landmarks = ?, leader = ? WHERE id = ?';
        $db->run( $sql, [ $population, $xp, $neighborhoods, json_encode( $landmarks_json ), $leader, $cityId ] );
        $messages['success'][] = 'saved';
        //reload because i'm lazy
        $sql = 'SELECT * FROM cities ORDER BY level';
        $cities = $db->fetchAll( $sql, [], true );       
    }
  
    if( $cityId > 0 ) {
        $city = $cities[$cityId];
        //make the landmarks nicer
        if(! empty( $city['landmarks'] ) ) {
            $landmarks = json_decode( $city['landmarks'], true );
            $str = '';
            $cnt = count( $landmarks );
            $i = 0;
            foreach( $landmarks as $key=>$info ) {
                $str .= "$key," . implode( ',',$info );
                $i++;
                if( $i < $cnt ) {
                    $str .= "\n";
                }
            }
            
            $city['landmarks'] = $str;
        }
    }
    

    /**
     * View
     */
    view:
        import( [ 'Template' ] );
        $vars = [];
        $vars['messages'] = $messages;
        $vars['presenter'] = 'cities';
        $vars['cities'] = $cities;
        $vars['city'] = $city;
        $vars['cityId'] = $cityId;
        
        

        $tpl = new Template();
        echo $tpl->twig->render( $view, $vars );
        exit;