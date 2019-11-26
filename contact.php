<?php

// hide all basic notices from PHP
error_reporting(E_ALL ^ E_NOTICE); 

if( isset($_POST['msg-submitted']) ) {
	$name = $_POST['name'];
	$email = $_POST['email'];
	$subject = $_POST['subject'];
	$message = $_POST['message'];

	// server validation
	if( trim($name) === '' ) {
		$nameError = 'Please provide your name.';
		$hasError = true;
	}

	if( trim($email) === '' ) {
		$emailError = 'Please provide your email address.';
		$hasError = true;
	} else if( !preg_match("/^[[:alnum:]][a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,4}$/i", trim($email)) ) {
		$emailError = 'Please provide valid email address.';
		$hasError = true;
	}

	if( trim($message) === '' ) {
		$messageError = "Please provide your message.";
		$hasError = true;
	} else {
		if( function_exists( 'stripslashes' ) ) {
			$message = stripslashes( trim( $message ) );
		}
	}
		
	if(!isset($hasError)) {
		
		$emailTo = 'sofiagalache@gmail.com';
		$subject = 'Nuevo mensaje a través de sofiagalache.com: ' . $name;
		$body = "Nombre: $name \n\nEmail: $email \n\nMensaje: $message";
		$headers = 'From: ' .' <'.$email.'>' . "\r\n" . 'Reply-To: ' . $email;

		if (mail($emailTo, $subject, $body, $headers)) {
			$message = 'Gracias ' . $name . ' por contactar conmigo. Estamos en contacto.';
		} else {
			$message = 'Ha habido algún problema para procesar el envío. Puedes probar a contactar conmigo a través de twitter.';
		}
        
        
		$result = true;
	
	} else {

		$arrMessage = array( $nameError, $emailError, $messageError );

		foreach ($arrMessage as $key => $value) {
			if( !isset($value) )
				unset($arrMessage[$key]);
		}

		$message = implode( '<br/>', $arrMessage );
		$result = false;
	}

	header("Content-type: application/json");
	echo json_encode( array( 'message' => $message, 'result' => $result ));
	die();
}


?>