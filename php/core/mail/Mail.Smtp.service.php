<?php 
require_once(SBSERVICE);
require_once(PHPMAILER);

/**
 *	@class MailSmtpService
 *	@desc Sends HTML mail using PHPMailer SMTP functions
 *
 *	@param to string To address [memory]
 *	@param subject string Subject [memory] 
 *	@param message string Message [memory] 
 *	@param mail array Mail configuration [Snowblozm] (type, host, port, secure, user, email, pass)
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *	
**/
class MailSmtpService implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('to', 'subject', 'message')
		);
	}

	/**
	 *	@interface Service
	**/
	public function run($memory){
		$mail = Snowblozm::get('mail');
		$to = $memory['to'];
		$subject = $memory['subject'];
		$message = $memory['message'];
		
		$mail = new PHPMailer();
		$mail->IsSMTP();
		$mail->SMTPAuth = true;
		$mail->SMTPSecure = $mail['secure'];
		$mail->Host = $mail['host'];
		$mail->Port = $mail['port'];

		$mail->Username = $mail['email'];
		$mail->Password = $mail['pass'];

		$mail->AddReplyTo($mail['email'], $mail['user']);
		$mail->From = $mail['email'];
		$mail->FromName = $mail['user'];

		$mail->Subject = $subject;
		$mail->WordWrap = 50;
		$mail->MsgHTML($message);
		
		$rcpts = explode(',', $to);
		foreach($rcpts as $rcpt){
			$mail->AddAddress($rcpt, "");
		}

		//$mail->AddAttachment("images/phpmailer.gif");             // attachment
		$mail->IsHTML(true);

		if(!$mail->Send()) {
			$memory['valid'] = false;
			$memory['msg'] = 'Error sending Mail';
			$memory['status'] = 503;
			$memory['details'] = 'Error : '.$mail->ErrorInfo.' @mail.smtp.service';
			return $memory;
		}
		
		$memory['valid'] = true;
		$memory['msg'] = 'Mail Accepted for Delivery';
		$memory['status'] = 200;
		$memory['details'] = 'Successfully executed';
		return $memory;
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array();
	}
	
}

?>