<?php
    /**
     * Eric Coumbe Name Generator::
     * 
     * @author gadhra <iam@stefans.computer>
     * @package PHP-Barebones
    */
    
    $method = Flight::get( 'method' );
    $messages = [ 'success'=>[], 'error'=> [] ];
    $view = 'bbc/gods.html';
    $names = [];

    import( 'Auth' );


    $auth = new Auth;
    if(! $auth->check() ) {
        Flight::redirect( '/login' );
        exit(0);
    }


    import( 'DB' );
    $db = new MySQL;

    $sql = 'SELECT g.id AS gid, th.throne AS throne_name, th.description, th.restrictions, th.benefit, g.name as god_name, g.sphere, g.holy_symbol, g.invocation, g.liturgy FROM thrones AS th LEFT JOIN gods AS g ON th.throne = g.throne ORDER BY th.throne, g.name';

    $rows = $db->fetchAll( $sql, [] );
    $gods = [];
    $throne = null;

    foreach( $rows as $row ) {
	if( $row['throne_name'] != $throne ) {
		$th = $row['throne_name'];
		list( $armor, $helmet, $shield, $weapon )  = explode( '::', $row['restrictions'] );
		$gods[$th]['armor'] = $armor;
		$gods[$th]['helmet'] = $helmet;
		$gods[$th]['shield'] = $shield;
		$gods[$th]['weapon'] = $weapon;
		$gods[$th]['description'] = $row['description'];
		$gods[$th]['name'] = $th;
		$gods[$th]['benefit'] = $row['benefit'];
		$gods[$th]['gods'] = [];
		$throne = $row['throne_name'];
	}

	$god = [];
	$god['gid'] = $row['gid'];
	$god['name'] = $row['god_name'];
	$god['sphere'] = $row['sphere'];
	$god['holy_symbol'] = $row['holy_symbol'];
	$god['invocation'] = $row['invocation'];
	$god['liturgy'] = $row['liturgy'];
	$gods[$th]['gods'][] = $god;
	$gods_by_id[$row['gid']] = $god;

    }


    if( $method == 'POST' ) {	
	if(! empty( $action ) && $action == 'save' ) {
	   $sql = 'UPDATE gods SET name = ?, sphere = ?, holy_symbol= ?, invocation=?, liturgy =? WHERE id = ?';
	   $db->run( $sql, [ $name, $sphere, $holy_symbol, $invocation, $liturgy, $id ] );
	   $messages['success'][] = 'saved';
	   // reload
           $sql = 'SELECT * FROM gods WHERE id = ?';
	   $gods = $db->fetchAll( $sql, [$id], true );
	}
    }

    if(! empty( $id ) && is_numeric( $id ) ) {
       $gods = $gods_by_id[$id];
       $view = 'bbc/god.html';
   }
        if(! empty( $id ) && $id == 'text' ) {
                $view = 'bbc/gods.txt';
        }




    /**
     * View
     */
    view:
        import( [ 'Template' ] );
        $vars = [];
        $vars['messages'] = $messages;
        $vars['presenter'] = 'gods';
        $vars['gods'] = $gods;
	$vars['thrones'] = $gods; // syntactic sugar
        

        $tpl = new Template();
        echo $tpl->twig->render( $view, $vars );
        exit;
