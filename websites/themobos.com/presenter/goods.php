<?php
    /**
     * Goods::
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
    $view = 'goods.html';
    $goodId = empty( $params['goodId'] ) ? 0 : ( int ) $params['goodId'];
    $city = null;
    
    
    function getMoney( $level ) {
        switch( $level ) {
            case 'low':
                return roll20( 'd10' ) * 50;
                break;
            case 'medium':
                return roll20( 'd10' ) * 500;
                break;
            case 'high':
                return roll20( 'd10' ) * 1000;
                break;
            case 'very high':
                return roll20( 'd10' ) * 5000;
                break;
            default:
                return 0;
                break;
        }        
    }
    
    function getBarter( $type ) {
        $mod = roll20( 'd4' );
        switch( $type ) {
            case 'low':
                return 14 + $mod;
                break;
            case 'medium':
                return 12 + $mod;
                break;
            case 'high':
                return 10 + $mod;
                break;
            default:
                return 20 + $mod;
                break;
        }
    }

    
    //load all the goods and buildings
    import( 'DB' );
    $db = new MySQL;
    
    $sql = 'SELECT * FROM goods ORDER BY name';
    $goods = $db->fetchAll( $sql, [], true );
    $good = null;
    
    $sql = 'SELECT * FROM buildings WHERE category IN ("craftsmen", "guild", "merchant") ORDER BY name';
    $buildings = $db->fetchAll( $sql, [], true );
    foreach( $buildings as $id=>$building ) {
        $building[$id]['checked'] = false;
    }
    
    
    $sql = 'SELECT * FROM buildings_goods';
    $links = $db->fetchAll( $sql, [] );

    
    if( $method === 'POST' && ! empty( $action ) && $action = 'good' ) { 
        $ins = 'INSERT INTO buildings_goods VALUES (?,?)';
        if(! empty( $building_link) ) {
            $db->run( 'DELETE FROM buildings_goods WHERE good_id = ?', [ $goodId ]);
            
            foreach( $building_link as $buildingId ) {
                $db->run( $ins, [ $buildingId, $goodId ]);
            }
        }
        $goodId = null;
    }
  
  
    $goodslist = [];
    
    if( $method === 'GET' && ! empty( $generate ) ) {
        $sql = 'SELECT b.id as bid, b.name as buildname, g.name as goodname, g.base_cost, bg.multiplier, b.barter, b.wealth FROM buildings AS b 
                JOIN buildings_goods AS bg ON b.id = bg.building_id 
                JOIN goods AS g ON bg.good_id = g.id ORDER BY b.name, g.name';
                
        $records = $db->fetchAll( $sql, [] );
        foreach( $records as $record ) {
            $b = $record['buildname'];
            if(! array_key_exists( $b, $goodslist ) ) {
                $goodslist[$b] = [];
                $goodslist[$b]['barter'] = 'DC ' . getBarter( $record['barter'] );
                $goodslist[$b]['money'] = getMoney( $record['wealth'] );
                $goodslist[$b]['inventory'] = [];
            }
            
            $cost = floor( $record['base_cost' ] * ( $record['multiplier'] / 100 ) ) . 'sil';
            $goodslist[$b]['inventory'][] = [ 'item'=>$record['goodname'], 'cost'=>$cost ];
        }
        $view = 'goodslist.html';
    }
  
    if( $goodId > 0 ) {
        $good = $goods[$goodId];
        // update buildings
        foreach( $links as $elem ) {
            if( $elem['good_id'] == $goodId ) {
                $buildings[$elem['building_id']]['checked'] = true;
            }
        }
    }
    

    /**
     * View
     */
    view:
        import( [ 'Template' ] );
        $vars = [];
        $vars['messages'] = $messages;
        $vars['presenter'] = 'goods';
        $vars['goods'] = $goods;
        $vars['good'] = $good;
        $vars['goodId'] = $goodId;
        $vars['buildings'] = $buildings;
        $vars['goodslist'] = $goodslist;
        
        

        $tpl = new Template();
        echo $tpl->twig->render( $view, $vars );
        exit;