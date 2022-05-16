<?php

error_reporting(E_ALL);

class Mailer{

	
	function sendEmailAttachment($email_from,$name_from,$email_to, $subject, $bodytext,$file_to_attach,$filename) {      
		require_once('smtpmail/PHPMailer.php');
		$mail = new PHPMailer();
		$mail->isSMTP();   
		// $mail->SMTPDebug = 0;//2;
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        $mail->SMTPSecure = 'tls'; 
        $mail->SMTPAuth = true;
        $mail->Username = 'kenyaeclectics@gmail.com';
        $mail->Password = 'eclectics.test';//T3st.1Pesa test.ipesa@gmail.com
		$mail->From      = $email_from;
		$mail->FromName  = $name_from;
		$mail->isHTML(true);
		$mail->Subject   = $subject;
		$mail->Body      = $bodytext;
		// $mail->Body =  html_entity_decode($bodytext);
		// $mail->AddAddress($email_to);
		$all_recipients = explode(',', $email_to);

		foreach ($all_recipients as $key => $value) {
		$mail->AddAddress($value);
		}
		// if(!empty($bcc)){
		//     $email->AddCC($cc);
		// }
		print_r($mail);exit;
		$mail->AddAttachment($file_to_attach , $filename, 'base64', 'application/octet-stream');
		
        //send the message, check for errors
        if (!$mail->send()) {

            //echo "Mailer Error: " . $mail->ErrorInfo;
            return false;
        } else {
            //echo "Message sent!";
            return true;

        }

	}
}