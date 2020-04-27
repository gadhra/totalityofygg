<?php
    /**
     * Metropolis::
     * 
     * @author gadhra <iam@stefans.computer>
     * @package PHP-Barebones
    */
    
    $method = Flight::get( 'method' );
    $messages = [ 'success'=>[], 'error'=> [] ];
    $view = 'metropolis.html';
    
    
    
    function getStreetName( $db, $place = null ) {
        $coinFlip = roll20( 'd2' ) % 2;
        $cats = [ 'any' ];
        if( $place ) {
            $cats[] = $place;
        }
        
        $base = "SELECT name FROM streets WHERE category IN (?) AND place = ? ORDER BY RAND() LIMIT 1";
        if( $coinFlip ) {
            $a = $db->fetchOne( $base, [ implode( ',', $cats ), 1] );
            $b = $db->fetchOne( $base, [ implode( ',', $cats ), 2] );
            $c = $db->fetchOne( $base, [ implode( ',', $cats ), 3] );

            return sprintf( "%s %s %s", $a, $b, $c );
        } else {
            $a = $db->fetchOne( $base, [ implode( ',', $cats ), 1] );
            $c = $db->fetchOne( $base, [ implode( ',', $cats ), 3] );
            return sprintf( "%s %s", $a, $c );
        }
    }
    
    function getLandmark( $db, $level ) {
        $sql = 'SELECT * FROM buildings WHERE min_level <= ? ORDER BY RAND() LIMIT 1';
        return $db->fetchRow( $sql, [ $level ] );
    }
    
    function getResources( $landmark ) {
        return 'bar';
    }
        

    function factor( $n ) {
        $return = [];
        for( $i = 1; $i <= sqrt( abs( $n ) ); $i++ ) {
            if( $n % $i == 0 ) {
                $z = $n / $i;
                array_push( $return, [$i, $z] );
            }
        }
        
        return $return;
    }
    
    import( 'DB' );    
    $db = new MySQL;
    
    
    // assume if post, etc. 
    $cityId = 6;
    $isTwisty = true;

    $sql = 'SELECT * FROM cities WHERE id = ?';
    $city = $db->fetchRow( $sql, [ $cityId ] );
    
    // get number of neighborhoods, population, etc.
    $population = roll20( $city['population'] );
    $neighborhoods = roll20( $city['neighborhoods'] );
    $landmarks = json_decode( $city['landmarks'], true );
    
    
    //generate all your landmarks
    $markers = [];
    $sql = 'SELECT category, COUNT(*) AS cnt FROM buildings GROUP BY category';
    $rows = $db->fetchAll( $sql, [] );
    $buildings = [];
    foreach( $rows as $row ) {
        $buildings[$row['category']] = $row['cnt'];
    }
        
    foreach( $landmarks as $category=>$info ) {
        $num = roll20( $info['num'] );
        $chance = roll20( $info['chance'] );
        $pct = roll20( 'd100' );
        
        if( $pct <= $chance ) {
            if( $num <= $buildings[$category] ) {
                $sql = 'SELECT * FROM buildings WHERE category = ? ORDER BY min_level ASC LIMIT ?';
                $rows = $db->fetchAll( $sql, [ $category, $num ] );
                foreach( $rows as $row ) {
                    $markers[] = $row;
                }
                
            } else {
                $diff = $num - $buildings[$category];
                $sql = 'SELECT * FROM buildings WHERE category = ?';
                $rows = $db->fetchAll( $sql, [ $category ] );
                foreach( $rows as $row ) {
                    $markers[] = $row;
                }
                
                while( $diff > 0 ) {
                    $sql = 'SELECT * FROM buildings WHERE category = ? ORDER BY RAND() LIMIT 1';
                    $markers[] = $db->fetchRow( $sql, [ $category ]);
                    $diff--;
                }
            }
        }
    }
    
    shuffle($markers);
    
    var_dump( $markers ); exit;
    
    //create the matrix
    $map = [];
    $factors = factor( $neighborhoods );
    $factor = $factors[count($factors)-1];
    shuffle( $factor );
    $rows = $factor[0];
    $cols = $factor[1];
    
    $row = 0;
    for( $col = 1; $col <= $neighborhoods; $col++ ) {
        $map[$row][$col] = ['links'=>[]];
            
        if( ( $col % $cols ) == 0 ) {
            $row++;
        }
    }
 
    // create links
    $row = 0;
    $start = true;
    $name = null;
    for( $col = 1; $col <= $neighborhoods; $col++ ) {
        $remainder = $col % $cols;

            

        //bottom only
        if(! $row ) {
            $map[$row][$col]['links'][] = $col + $cols . 'S';
            $up = getStreetName( $db );
        } elseif( $row == $rows -1 ) {
            $map[$row][$col]['links'][] = $col - $cols . 'N';
        } else {
            $map[$row][$col]['links'][] = $col - $cols . 'N';
            $map[$row][$col]['links'][] = $col + $cols . 'S';
        }

        // right
        if( $start ) {
            $map[$row][$col]['links'][] = $col + 1 . 'E';
            $start = false;
            $cross = getStreetName( $db );            

        } elseif(! $remainder ) { // left only, last in row
            $map[$row][$col]['links'][] = $col - 1 . 'W';
            $row++;
            $start = true;
        } else {
            $map[$row][$col]['links'][] = $col + 1 . 'E';
            $map[$row][$col]['links'][] = $col - 1 . 'W';
        }
        
        $map[$row][$col]['street']['up'] = $up;
        $map[$row][$col]['street']['cross'] = $cross;
    }
    
    //var_dump( $map ); exit;
        
    // flatten the matrix
    $streets = [];
    foreach( $map as $row=>$array ) {
        foreach( $array as $i=>$j ) {
            $streets[$i] = $j;
        }
    } 
        
/*
    // are the streets twisty?  Let's mix it up, say 1/2 are twisty
    if( $isTwisty ) {
        $opts = [ 'NW'=>'SE', 'NE'=>'SW', 'SE'=>'NW', 'SW'=>'NE' ];
        
        foreach( $streets as $streetnum => $links ) {

            $cnt = count( $links['links'] );
            
            // don't mess with the corners or edges, too much work
            if( $cnt < 4 ) {
                continue;
            }
            

            $x = roll20( 'd2' );
            if(! $x % 2 ) {
                continue;
            }
            
            $x = roll20( 'd2' );
            $coinFlip = true;
            if(! $x % 2 ) {
                $coinFlip = false;
            }
                        

            for( $i = 0; $i < $cnt; $i++ ) {
                preg_match( '/([0-9]+)([N|S|E|W]{1})/sU', $links['links'][$i], $matches );
                
                $linknumber = $matches[1];
                $linkdirect = $matches[2];
                                
                switch( $linkdirect ) {
                    case 'N':
                        $direction = 'NE';
                        if( $coinFlip ) {
                            $direction = 'NW';
                        }                        
                        break;
                        
                    case 'S':
                        $direction = 'SE';
                        if( $coinFlip ) {
                            $direction = 'SW';
                        }                  
                        break;
                    case 'E':
                        $direction = 'NE';
                        if( $coinFlip ) {
                            $direction = 'SE';
                        }                   
                        break;
                    case 'W':
                        $direction = 'NW';
                        if( $coinFlip ) {
                            $direction = 'SW';
                        }                 
                        break;
                    default:
                        continue;
                        break;
                }
                
                $streets[$linknumber]['links'][$i] =  sprintf( "%d%s", $streetnum, $opts[$direction] );
                $streets[$streetnum]['links'][$i] = sprintf( "%d%s", $linknumber, $direction );
            }
        }
    }
    
*/
    //var_dump( $streets ); exit;
    
    
    // add the landmarks
    foreach( $markers as $marker ) {
        $idx = array_rand( $streets );
        if( array_key_exists( 'buildings', $streets[$idx] ) ) {
            $streets[$idx]['buildings'][] = $marker;
        } else {
            $streets[$idx]['buildings'] = [ $marker ];
        }
    }

    

    /**
     * View
     */
    view:
        import( [ 'Template' ] );
        $vars = [];
        $vars['messages'] = $messages;
        $vars['presenter'] = 'metropolis';
        $vars['streets'] = $streets;
        
        $tpl = new Template();
        echo $tpl->twig->render( $view, $vars );
        exit;