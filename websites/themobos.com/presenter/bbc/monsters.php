<?php
    /**
     * Eric Coumbe Name Generator::
     * 
     * @author gadhra <iam@stefans.computer>
     * @package PHP-Barebones
    */
    
    $method = Flight::get( 'method' );
    $messages = [ 'success'=>[], 'error'=> [] ];
    $view = 'bbc/monsters.html';
    $names = [];

    import( 'Auth' );


    $auth = new Auth;
    if(! $auth->check() ) {
        Flight::redirect( '/login' );
        exit(0);
    }


    import( 'DB' );
    $db = new MySQL;

    $sql = 'SELECT * FROM monsters ORDER BY Type, HIT';
    $rows = $db->fetchAll( $sql, [] );
    $monsters = [];
    foreach( $rows as $row ) {
        $monsters[$row['Type']][] = $row;
	$monsters_by_id[$row['id']] = $row;
    }

    if( $method == 'POST' ) {
	if(! empty( $action ) && $action == 'save' ) {
		$sql = 'UPDATE monsters SET Name=?, Type=?, MV=?, WK=?, DMG=?,RNG=?,HIT=?,POWER=?,SOAK=?,MORALE=?,SAVE=?,Atts=?,notes=? WHERE id = ?';
		$db->run( $sql, [ $Name, $Type, $MV, $WK, $DMG, $RNG, $HIT, $POWER, $SOAK, $MORALE, $SAVE, $Atts, $notes, $id ] );
		$messages['success'][] = 'saved';
                // reload
                $sql = 'SELECT * FROM monsters ORDER BY Type, HIT';
                $monsters = $db->fetchAll( $sql, [], true );
	}
    }

    if(! empty( $id ) && is_numeric( $id ) ) {
       $monsters = $monsters_by_id[$id];
       $view = 'bbc/monster.html';
   }




    /**
     * View
     */
    view:
        import( [ 'Template' ] );
        $vars = [];
        $vars['messages'] = $messages;
        $vars['presenter'] = 'monsters';
        $vars['monsters'] = $monsters;
        

        $tpl = new Template();
        echo $tpl->twig->render( $view, $vars );
        exit;
