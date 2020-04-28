<?php
    /**
     * Last Gasp generators::
     * 
     * @author gadhra <iam@stefans.computer>
     * @package PHP-Barebones
    */
    
    $method = Flight::get( 'method' );
    $messages = [ 'success'=>[], 'error'=> [] ];
    $view = 'lastgasp.html';

    /**
     * View
     */
    view:
        import( [ 'Template' ] );
        $vars = [];
        $vars['messages'] = $messages;
        $vars['presenter'] = 'lastgasp';
        

        $tpl = new Template();
        echo $tpl->twig->render( $view, $vars );
        exit;
