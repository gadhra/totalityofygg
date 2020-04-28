<?php
    /**
     * Eric Coumbe Name Generator::
     * 
     * @author gadhra <iam@stefans.computer>
     * @package PHP-Barebones
    */
    
    $method = Flight::get( 'method' );
    $messages = [ 'success'=>[], 'error'=> [] ];
    $view = 'monsters.html';
    $names = [];

    import( 'DB' );
    $db = new MySQL;

    $sql = 'SELECT * FROM monsters ORDER BY Type, HIT';
    $rows = $db->fetchAll( $sql, [] );
    $monsters = [];
    foreach( $rows as $row ) {
        $monsters[$row['Type']][] = $row;
    }

    $sql = 'SELECT * FROM monster_types';
    $rows = $db->fetchAll( $sql, [] );
    $types = [];
    foreach( $rows as $row ) {
        $types[$row['name']] = $row;
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
        $vars['types'] = $types;
        

        $tpl = new Template();
        echo $tpl->twig->render( $view, $vars );
        exit;
