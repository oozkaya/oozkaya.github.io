<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
#require_once '/opt/atlassian/pipelines/agent/build/vendor/autoload.php';
require_once '/app/vendor/autoload.php'

// Replace this with your own email address
$siteOwnersEmail = 'yuonur@gmail.com';


if($_POST) {

   $name = trim(stripslashes($_POST['contactName']));
   $email = trim(stripslashes($_POST['contactEmail']));
   $subject = trim(stripslashes($_POST['contactSubject']));
   $contact_message = trim(stripslashes($_POST['contactMessage']));

   // Check Name
	if (strlen($name) < 2) {
		$error['name'] = "Veuillez entrer votre nom.";
	}
	// Check Email
	if (!preg_match('/^[a-z0-9&\'\.\-_\+]+@[a-z0-9\-]+\.([a-z0-9\-]+\.)*+[a-z]{2}/is', $email)) {
		$error['email'] = "Please enter a valid email address.";
	}
	// Check Message
	if (strlen($contact_message) < 15) {
		$error['message'] = "Please enter your message. It should have at least 15 characters.";
	}
   // Subject
	if ($subject == '') { $subject = "Contact Form Submission"; }


   // Set Message
   $message ='';
   $message .= "Email from: " . $name . "<br />";
	$message .= "Email address: " . $email . "<br />";
   $message .= "Message: <br />";
   $message .= $contact_message;
   $message .= "<br /> ----- <br /> This email was sent from your site's contact form. <br />";

   // Set From: header
   $from =  $name . " <" . $email . ">";

   // Email Headers
	$headers = "From: " . $from . "\r\n";
	$headers .= "Reply-To: ". $email . "\r\n";
 	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

##	$from = new SendGrid\Email($name, $email);
##	$to = new SendGrid\Email("Onur OZKAYA", $siteOwnersEmail);
##	$content = new SendGrid\Content("text/plain", $message);

	if (isset($error)) {

		$response = (isset($error['name'])) ? $error['name'] . "<br /> \n" : null;
		$response .= (isset($error['email'])) ? $error['email'] . "<br /> \n" : null;
		$response .= (isset($error['message'])) ? $error['message'] . "<br />" : null;
		
		echo $response;

	} # end if - there was a validation error
    else {

      ini_set("sendmail_from", $siteOwnersEmail); // for windows server
      $mail = mail($siteOwnersEmail, $subject, $message); #, $headers);
	 echo '<script>console.log("'.$siteOwnersEmail.'")</script>';
	 echo '<script>console.log("'.$subject.'")</script>';
	 echo '<script>console.log("'.$message.'")</script>';
	#echo '<script>console.log("'.$headers.'")</script>';
##		$mail = new SendGrid\Mail($from, $subject, $to, $content);

##		$sg = new \SendGrid($apiKey);
		
##		$response = $sg->client->mail()->send()->post($mail);
##		echo $response->statusCode();
##		echo $response->headers();
##		echo $response->body();
		if ($mail) { echo "OK"; }
      else { echo "Something went wrong. Please try again."; }
		
	} # end if - no validation error

}

?>
