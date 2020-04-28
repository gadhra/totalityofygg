<?php
    /**
     * Eric Coumbe Name Generator::
     * 
     * @author gadhra <iam@stefans.computer>
     * @package PHP-Barebones
    */
    
    $method = Flight::get( 'method' );
    $messages = [ 'success'=>[], 'error'=> [] ];
    $view = 'spells.html';
    $names = [];


    $limit = 20;

	if( $method == 'POST')  {
		$noun = file( '/srv/themobos.com/webroot/data/demon_names.txt' );
		$verb = file( '/srv/themobos.com/webroot/data/spell_verbs.txt' );
		shuffle( $noun ); shuffle( $verb );
		for( $i = 0; $i < $limit; $i++ ) {
			if( rand( 0, 1 ) % 2 ) {
				$names[] = sprintf( "%s of %s", $verb[$i], $noun[$i] );
			} else {
				$names[] = sprintf( "%s's %s", $noun[$i], $verb[$i] );
			}
		}
	}
    /**
     * View
     */
    view:
        import( [ 'Template' ] );
        $vars = [];
        $vars['messages'] = $messages;
        $vars['presenter'] = 'spells';
        $vars['names'] = $names;
        

        $tpl = new Template();
        echo $tpl->twig->render( $view, $vars );
        exit;
