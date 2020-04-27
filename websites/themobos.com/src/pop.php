#!/usr/bin/php
<?php
$output = file( 'forgotten.txt' );
$names = $desc = array();
foreach( $output as $line ) {
	$line = trim( $line );
	if(! $line ) {
		continue;
	}

	if(! $i ) {
		$names[] = preg_replace( '/^[0-9].*\.(.*)/', trim('$1'), $line );
	//	$names[] = $line;
		$i++;
		continue;
	}
	$desc[] = $line;
	$i = 0;	
}

$ins = 'INSERT INTO forgotten VALUES (null, ?, ?)';
$cnt = count( $names );
$db = new MySQL;

for( $i = 0; $i < $cnt; $i++ ) {
	$db->run( $ins, [ $names[$i], $desc[$i] );	

}
?>
