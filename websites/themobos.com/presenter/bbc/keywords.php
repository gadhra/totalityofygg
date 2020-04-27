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
    $view = 'bbc/keywords.html';
    
    import( 'DB' );
    $db = new MySQL;
    $sql = 'SELECT * FROM keywords ORDER BY name';
    $keywords = $db->fetchAll( $sql, [], true );
    $keyword = null;


 	if( $method == 'POST' ) {
		if(! empty( $action ) && $action == 'keyword' ) {
			if( $id == 0 ) {
				$sql = 'INSERT INTO keywords VALUES (null, ?, ? )';
				$db->run( $sql, [ $name, $description ] );
			} else {
				$sql = 'UPDATE keywords SET name = ?, description = ? WHERE id = ?';
				$db->run( $sql, [ $name, $description,  $id ] );
			}	
			$messages['success'][] = 'saved';
			// reload
			$sql = 'SELECT * FROM keywords ORDER BY name';
			$keywords  = $db->fetchAll( $sql, [], true );
		} elseif(! empty( $spell_id ) ) {
			Flight::redirect( '/bbc/keywords/' . $spell_id );
			exit(0);
		}
	} 

	if(! empty( $id ) && $id == 'new' ) {
		$keyword['id'] = 0;
		$view =  'bbc/keyword.html';
	}	

	if(! empty( $id ) && is_numeric( $id ) ) {
		$keyword = $keywords[$id];
		$view = 'bbc/keyword.html';
	}

	


    /**
     * View
     */
    view:
        import( [ 'Template' ] );
        $vars = [];
        $vars['messages'] = $messages;
        $vars['presenter'] = 'keywords';
        $vars['keywords'] = $keywords;
	$vars['keyword'] = $keyword;

        $tpl = new Template();
        echo $tpl->twig->render( $view, $vars );
        exit;
