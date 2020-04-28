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
    $view = 'bbc/mysteries.html';
    
    //load all the cities
    import( 'DB' );
    $db = new MySQL;
    $sql = 'SELECT * FROM wizardry ORDER BY name';
    $spells = $db->fetchAll( $sql, [], true );
    $spell = null;

 	if( $method == 'POST' ) {
		if(! empty( $action ) && $action == 'save' ) {
			$sql = 'UPDATE wizardry SET name = ?, paradigm = ?, target = ?, duration = ?, keywords = ?, description = ?, save = ? WHERE id = ?';
			$save = 0;
			if(! empty( $getSave ) && ! empty( $getSave[0] ) ) {
				$save = $getSave[0];
			}
			$db->run( $sql, [ $name, $paradigm, $target, $duration, $keywords, $description, $save, $id ] );
			$messages['success'][] = 'saved';
			// reload
			$sql = 'SELECT * FROM wizardry ORDER BY name';
			$spells = $db->fetchAll( $sql, [], true );
		} elseif(! empty( $spell_id ) ) {
			Flight::redirect( '/bbc/mysteries/' . $spell_id );
			exit(0);
		}
	} 
   
	if(! empty( $id ) && is_numeric( $id ) ) {
		$spell = $spells[$id];
		$view = 'bbc/mystery.html';
	}

	if(! empty( $id ) && $id == 'gmbinder' ) {
		$view = 'bbc/gmbinder.txt';
	}

    /**
     * View
     */
    view:
        import( [ 'Template' ] );
        $vars = [];
        $vars['messages'] = $messages;
        $vars['presenter'] = 'mysteries';
        $vars['mysteries'] = $spells;
	$vars['mystery'] = $spell;
	$vars['paradigms'] = array( 'Biomancy', 'Elements', 'Entropy', 'Force', 'Mind', 'Prophesy' );

        $tpl = new Template();
        echo $tpl->twig->render( $view, $vars );
        exit;
