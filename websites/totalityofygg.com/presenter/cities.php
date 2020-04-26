<?php
    /**
     * Main::
     * 
     * @author gadhra <iam@stefans.computer>
     * @package PHP-Barebones
    */

    $messages = [ 'success'=>[], 'error'=> [] ];
    import( [ 'Template' ] );
    $view = 'cities.html';

    //load all the cities
    import( 'DB' );
    $db = new MySQL;
    $sql = 'SELECT * FROM cities ORDER BY population DESC';
    $cities = $db->fetchAll( $sql, [], true );




    /**
     * View
     */
    view:
        $vars = [];
        $vars['messages'] = $messages;
        $vars['presenter'] = 'cities';
        $vars['cities'] = $cities;

        $tpl = new Template();
        echo $tpl->twig->render( $view, $vars );
        exit;
