<?php
    /**
     * Main::
     * 
     * @author gadhra <iam@stefans.computer>
     * @package PHP-Barebones
    */

    $messages = [ 'success'=>[], 'error'=> [] ];
    import( [ 'Template' ] );
    $view = 'visuals.html';





    /**
     * View
     */
    view:
        $vars = [];
        $vars['messages'] = $messages;
        $vars['presenter'] = 'visuals';

        $tpl = new Template();
        echo $tpl->twig->render( $view, $vars );
        exit;
