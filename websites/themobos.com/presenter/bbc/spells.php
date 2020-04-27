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
    $view = 'bbc/spells.html';
    
    //load all the cities
    import( 'DB' );
    $db = new MySQL;
    $sql = 'SELECT * FROM spells ORDER BY name';
    $spells = $db->fetchAll( $sql, [], true );
    $spell = null;


 	if( $method == 'POST' ) {
		if(! empty( $action ) && $action == 'save' ) {
			$sql = 'UPDATE spells SET name = ?, counterspell = ?, duration = ?, save = ?, target = ?, paradigm = ?, description = ? WHERE id = ?';

			$db->run( $sql, [ $name, $counterspell, $duration, $save, $target, $paradigm, $description, $id ] );
			$messages['success'][] = 'saved';
			// reload
			$sql = 'SELECT * FROM spells ORDER BY name';
			$spells = $db->fetchAll( $sql, [], true );
		} elseif(! empty( $spell_id ) ) {
			Flight::redirect( '/bbc/spell/' . $spell_id );
			exit(0);
		}
	} 
  
	if(! empty( $id ) && is_numeric( $id ) ) {
		$spell = $spells[$id];
		$view = 'bbc/spell.html';
	}

	if(! empty( $id ) && $id == 'gmbinder' ) {
		$view = 'bbc/spells.txt';
	}

    /**
     * View
     */
    view:
        import( [ 'Template' ] );
        $vars = [];
        $vars['messages'] = $messages;
        $vars['presenter'] = 'spells';
        $vars['spells'] = $spells;
	$vars['spell'] = $spell;
	$vars['paradigms'] = array( 'Biomancy', 'Elements', 'Entropy', 'Force', 'Mind', 'Prophesy' );

        $tpl = new Template();
        echo $tpl->twig->render( $view, $vars );
        exit;
