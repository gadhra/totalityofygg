<?php
    /**
     * Main::
     * 
     * @author gadhra <iam@stefans.computer>
     * @package PHP-Barebones
    */

    $messages = [ 'success'=>[], 'error'=> [] ];
    import( [ 'Template' ] );
    $view = 'rules.html';

    // handle errata
	$h = fopen( dirname(__FILE__) . '/../webroot/data/errata.txt', 'r' );
	$errata = [];
	$i = 0;
	while(! feof( $h ) ) {
		$line = trim( fgets ($h ) );
		$errata[] = $line;
    }

	fclose( $h );

    /**
     * View
     */
    view:
        $vars = [];
        $vars['messages'] = $messages;
        $vars['presenter'] = 'rules';
		$vars['errata'] = $errata;

        $tpl = new Template();
        echo $tpl->twig->render( $view, $vars );
        exit;
