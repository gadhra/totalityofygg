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
    $view = 'bbc/liturgies.html';
    
    import( 'DB' );
    $db = new MySQL;
    $sql = 'SELECT * FROM liturgies ORDER BY name';
    $liturgies = $db->fetchAll( $sql, [], true );
    $liturgy = null;

    $sql = 'SELECT id, name FROM gods ORDER BY name';
    $gods = $db->fetchAll( $sql, [], true );


 	if( $method == 'POST' ) {
		if(! empty( $action ) && $action == 'save' ) {
			$sql = 'UPDATE liturgies SET god_id = ?, name = ?, duration = ?, save = ?, target = ?, paradigm = ?, description = ? WHERE id = ?';

			$db->run( $sql, [ $god_id, $name, $duration, $save, $target, $paradigm, $description, $id ] );
			$messages['success'][] = 'saved';
			// reload
			$sql = 'SELECT * FROM liturgies ORDER BY name';
			$liturgies = $db->fetchAll( $sql, [], true );
		} elseif(! empty( $liturgy_id ) ) {
			Flight::redirect( '/bbc/liturgy/' . $liturgy_id );
			exit(0);
		}
	} 
  
	if(! empty( $id ) && is_numeric( $id ) ) {
		$liturgy = $liturgies[$id];
		$view = 'bbc/liturgy.html';
	}

	if(! empty( $id ) && $id == 'gmbinder' ) {
		$sql = 'SELECT l.*, g.name AS godname FROM liturgies AS l JOIN gods AS g ON l.god_id = g.id ORDER BY g.name, l.name';
		$rows = $db->fetchAll( $sql, [], true );
		$liturgies = [];
		$god = null;
		foreach( $rows as $row ) {
			if( $row['godname'] != $god ) {
				$god = $row['godname'];
			}
			$liturgies[$god]['liturgies'][]= $row;
		}	

		$view = 'bbc/liturgies.txt';
	}

    /**
     * View
     */
    view:
        import( [ 'Template' ] );
        $vars = [];
        $vars['messages'] = $messages;
        $vars['presenter'] = 'liturgies';
        $vars['liturgies'] = $liturgies;
	$vars['liturgy'] = $liturgy;
	$vars['gods'] = $gods;
	$vars['paradigms'] = array( 'Biomancy', 'Elements', 'Entropy', 'Force', 'Mind', 'Prophesy' );

        $tpl = new Template();
        echo $tpl->twig->render( $view, $vars );
        exit;
