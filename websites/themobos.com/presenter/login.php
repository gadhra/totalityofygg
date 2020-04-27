<?php
    
    import( 'Auth' );
    $auth = new Auth;
    $method = Flight::get( 'method' );
    $messages = [ 'success'=>[], 'error'=> [] ];
    $view = 'login.html';
    
    if( $method === 'POST' ) {
        if( empty( $u ) || empty( $p ) ) {
            $messages['error'][] = 'Username and password cannot be empty';
            goto view;
        }
        $ok = $auth->login( $u, $p );
        if(! $ok ) {
            $messages['error'][] = 'Invalid login';
            goto view;
        }
    }

    /**
     * View
     */
    view:
        import( [ 'Template' ] );
        $vars = [];
        $vars['messages'] = $messages;
        $vars['presenter'] = 'login';
        

        $tpl = new Template();
        echo $tpl->twig->render( $view, $vars );
        exit;    
