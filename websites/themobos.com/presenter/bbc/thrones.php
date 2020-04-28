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
    $view = 'bbc/thrones.html';
    
    //load all the cities
    import( 'DB' );
    $db = new MySQL;
    $sql = 'SELECT * FROM thrones ORDER BY throne';
    $thrones = $db->fetchAll( $sql, [], true );
    $throne = null;


 	if( $method == 'POST' ) {
		if(! empty( $action ) && $action == 'save' ) {
			$sql = 'UPDATE thrones SET benefit=?, restrictions=?, description = ? WHERE id = ?';

			$db->run( $sql, [ $benefit, $restrictions,$description, $id ] );
			$messages['success'][] = 'saved';
			// reload
			$sql = 'SELECT * FROM thrones ORDER BY throne';
			$thrones = $db->fetchAll( $sql, [], true );
		} elseif(! empty( $spell_id ) ) {
			Flight::redirect( '/bbc/throne/' . $throne_id );
			exit(0);
		}
	} 
  
	if(! empty( $id ) && is_numeric( $id ) ) {
		$throne = $thrones[$id];
		$view = 'bbc/throne.html';
	}

	if(! empty( $id ) && $id == 'gmbinder' ) {
		$view = 'bbc/thrones.txt';
	}

    /**
     * View
     */
    view:
        import( [ 'Template' ] );
        $vars = [];
        $vars['messages'] = $messages;
        $vars['presenter'] = 'thrones';
        $vars['thrones'] = $thrones;
	$vars['throne'] = $throne;

        $tpl = new Template();
        echo $tpl->twig->render( $view, $vars );
        exit;
