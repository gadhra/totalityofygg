<?php
    /**
     * Main::
     * 
     * @author gadhra <iam@stefans.computer>
     * @package PHP-Barebones
    */

    $messages = [ 'success'=>[], 'error'=> [] ];
    import( [ 'Template' ] );
    $view = 'cities_o.txt';

    //load all the cities
    import( 'DB' );
    $db = new MySQL;
    $sql = 'SELECT * FROM cities ORDER BY population DESC, province ASC, state ASC';
    $cities = $db->fetchAll( $sql, [], true );




    /**
     * View
     */
    view:
        $vars = [];
        $vars['messages'] = $messages;
        $vars['presenter'] = 'cities_o';
        $vars['cities'] = $cities;

        $tpl = new Template();
        echo $tpl->twig->render( $view, $vars );
        exit;
