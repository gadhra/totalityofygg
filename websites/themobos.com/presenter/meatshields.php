<?php
    /**
     * Meatshields::
     * 
     * @author gadhra <iam@stefans.computer>
     * @package PHP-Barebones
    */
    
    $method = Flight::get( 'method' );
    $messages = [ 'success'=>[], 'error'=> [] ];
    $view = 'meatshields.html';
    $meatshieldId = empty( $params['meatshield'] ) ? 0 : ( int ) $params['meatshield'];

    
    import( 'DB' );    
    $db = new MySQL;   
   
    $meats = []; 
    $sql = 'SELECT * FROM meatshields WHERE active = 1 ORDER BY name';
    $rows = $db->fetchAll( $sql, [] );
    foreach( $rows as $row ) {
	$meats[$row['id']] = $row;
    }
   
    // build the query
    $meatshield = [];
    if( $meatshieldId > 0 ) {
        $record = $meats[$meatshieldId];
        
        // generate armor and shield
        $equipment = '';
        $ac = 10;
        
        // meatshields get a better chance at armor
        $ac_bonus = 0;
        if( $meatshieldId == 2 ) {
            $ac_bonus = 25;
        }
        
        $roll = roll20( 'd100' ) + $ac_bonus;
        $sql = 'SELECT * FROM armor WHERE odds <= ? ORDER BY odds DESC LIMIT 1';
        $armor = $db->fetchRow( $sql, [ $roll ] );
        if( empty( $armor ) ) {
            $armor['name'] = 'Unarmored';
            $armor['bonus'] = 0;
        }
        
        $equipment .= $armor['name'];
        $ac += $armor['bonus'];
        
        if( $record['shield_chance'] > 0 ) {
            $roll = roll20( 'd100' );
            if( $record['shield_chance'] <= $roll ) {
                $equipment .= " & shield";
                $ac++;
            }
        }

        
        $sql = 'SELECT name FROM meatshield_names WHERE (force_name = ? || force_name IS NULL) AND  meatshieldId = ? ORDER BY RAND() LIMIT 1';
        
        $meatshield['name'] = $db->fetchOne( $sql, [ 'first', $meatshieldId ] ) . ' ' . $db->fetchOne( $sql, [ 'last', $meatshieldId ] );
        $meatshield['class'] = $record['name'];
        $meatshield['hp'] = roll20( $record['hp'] );
        $meatshield['ac'] = $ac;
        $meatshield['description'] = $record['description'];
        $meatshield['equipment'] = $equipment . '/' . $record['weapon'];
        $meatshield['morale'] = $record['morale'];
        $meatshield['cost'] = $record['cost'];
    }


    /**
     * View
     */
    view:
        import( [ 'Template' ] );
        $vars = [];
        $vars['messages'] = $messages;
        $vars['presenter'] = 'meatshields';
        $vars['meats'] = $meats;
        $vars['meatshield'] = $meatshield;
        $vars['meatshieldId'] = $meatshieldId;
        

        $tpl = new Template();
        echo $tpl->twig->render( $view, $vars );
        exit;
