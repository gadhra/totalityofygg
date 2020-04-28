<?php

	if( $_POST['token'] !== $slack_verify_token ) {
 		header("HTTP/1.0 403 Forbidden");
		exit(0);
	}

	$cmd = $_POST['command'];

	switch( $cmd ) {
		case '/ttz':
			$output = [];
			$output['type'] = 'image';
			$output['title'] = [];
			$output['title']['type'] = 'plain_text';
			$output['title']['text'] = 'Train to Zone!!1';
			$output['title']['emoji'] = true;

			$output['image_url'] = 'https://media.giphy.com/media/eH9unDlYow00o9O1Lj/giphy.gif';
			$output['alt_text'] = 'Train to Zone!!1';

			echo json_encode( $output );

			break;
		default:
			header("HTTP/1.0 405 Method Not Allowed");
			exit;
			break;
	}


	function dispatch_message( $payload ) {
		$ch = curl_init( 'https://slack.com/api/chat.postMessage' );
		$headers = [];
		$headers[] = 'Content-type: application/json';
		$headers[] = 'Authorization: ' . $foo;
		curl_setopt( $ch, CURLOPT_POST, 1 );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $output );
	}

	exit(0);
?>
