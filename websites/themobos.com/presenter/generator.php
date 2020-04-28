<?php
	$gen = Flight::get( 'gen' );
	$limit = Flight::get( 'limit' );
	$elem = [];
	$names = [];


	$file = sprintf( dirname(__FILE__) . '/../webroot/data/%s.txt', $gen );
	$data = parse_ini_file( $file, true );
	$outcnt = count( $data['output'] ); // number of potential outputs
	$fieldcnt = count( $data['field'] ); // number of fields that build the sprintf

	
	// randomize the elements
	foreach( $data['field'] as $field ) {
		$elem[$field] = $data[$field]['value'];
		shuffle( $elem[$field] );
	}

	// first element is printf format, followed by the fields i.e. '%s of %s|field1|field2'
	for( $i = 0; $i < $limit; $i++ ) {
		$j = rand(0,$outcnt-1);
		$entry = $data['output'][$j];
		$args = explode( '|', $entry );
		$format = array_shift( $args );
		$strargs = [];

		for( $k = 0; $k < $fieldcnt; $k++ ) {
			$ptr = $elem[$args[$k]];
			$rand = rand( 0, count( $ptr )-1 );
			$strargs[] = $ptr[$rand];
		}
		$names[] = vsprintf( $format, $strargs ); 
	}

	header( 'Content-type', 'application/json' );
	echo json_encode( $names );
	exit;
?>
