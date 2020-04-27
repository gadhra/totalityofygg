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
    $view = 'bbc/forgotten.html';
    
    import( 'DB' );
    $db = new MySQL;

    // Preload all values
    $sql = 'SELECT * FROM forgotten WHERE description != "" ORDER BY name';
    $res = $db->fetchAll( $sql, [], true );
    $forgotten = [];
    $forgotten_by_id = [];
    foreach( $res as $row ) {
        $forgotten[$row['origin']][] = $row;
        $forgotten_by_id[$row['id']] = $row;
    }
     
    $sql = 'SELECT * FROM keywords ORDER BY name';
    $keywords = $db->fetchAll( $sql, [], true );


    $sql = 'SELECT * FROM forgotten_keywords ORDER BY forgotten_id';
    $kw_tmp = $db->fetchAll( $sql, [], false );
    $kw_map = [];

    foreach( $kw_tmp as $ignore=>$array ) {
	$kw_map[$array['forgotten_id']][] = $array['keyword_id'];
    }

    $kw_checked = [];


 	if( $method == 'POST' ) {
		if(! empty( $action ) && $action == 'save' ) {
			$sql = 'UPDATE forgotten SET name = ?, description = ?, origin = ? WHERE id = ?';
			$db->run( $sql, [ $name, $description, $origin, $id ] );

			$sql = 'DELETE FROM forgotten_keywords WHERE forgotten_id = ?';
			$db->run( $sql, [ $id ] );
			
			if(! empty( $keyword ) ) {
				foreach( $keyword as $kid ) { 
					$db->run( 'INSERT INTO forgotten_keywords 
						( forgotten_id, keyword_id ) VALUES (?,?)', 
						[ $id, $kid ] );
				}
			} 
			$messages['success'][] = 'saved';
			// reload
			$sql = 'SELECT * FROM forgotten ORDER BY name';
			$forgotten = $db->fetchAll( $sql, [], true );
		} elseif(! empty( $id ) ) {
			Flight::redirect( '/bbc/forgotten/' . $id );
			exit(0);
		}
	} 
   
	if(! empty( $id ) && is_numeric( $id ) ) {
		$forgotten = $forgotten_by_id[$id];
		$view = 'bbc/forgotten_single.html';
		if( array_key_exists( $id, $kw_map ) ) {
			$kw_checked = $kw_map[$id];
		}
	}


	if(! empty( $id ) && $id == 'text' ) {
		$view = 'bbc/forgotten.txt';
	}


    /**
     * View
     */
    view:
        import( [ 'Template' ] );
        $vars = [];
        $vars['messages'] = $messages;
        $vars['presenter'] = 'forgotten';
        $vars['forgotten'] = $forgotten;
        $vars['origin'] = [ 'Archon', 'Fiend', 'Seraph' ];
	$vars['keywords'] = $keywords;
	$vars['kw_checked'] = $kw_checked;

        $tpl = new Template();
        echo $tpl->twig->render( $view, $vars );
        exit;
