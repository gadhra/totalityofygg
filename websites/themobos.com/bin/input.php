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
    $db->run( 'TRUNCATE TABLE gods' );

$row = 1;
if (($handle = fopen(dirname(__FILE__) . "/small_gods.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $num = count($data);
        $row++;
	if( $row == 2) continue;
	list( $name, $throne, $sphere, $holy_symbol, $invocation, $liturgy, $ignore ) = $data;
	$ins = 'INSERT INTO gods VALUES( NULL, ?, ?, ?, ?, ?, ? )';
	$db->run( $ins, [ $name, $throne, $sphere, $holy_symbol, $invocation, $liturgy ] );

    }
    fclose($handle);
}
?>

