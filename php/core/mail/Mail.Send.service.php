<?php 
require_once(SBSERVICE);

/**
 *	@class MailSendService
 *	@desc Sends mail using mail() function
 *
 *	@param to string To address [memory]
 *	@param subject string Subject [memory] 
 *	@param message string Message [memory] 
 *	@param headers string Additional headers [memory] optional default 'From: '.$mail['user'].' <'.$mail['email'].'>\r\nReply-To: '.$mail['user'].' <'.$mail['email'].'>\r\nX-Mailer: PHP/'.phpversion()
 *	@param params string Additional parameters [memory] optional default ''
 *	@param mail array Mail configuration [Snowblozm] (type, host, port, secure, user, email, pass)
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *	
**/
class MailSendService implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('to', 'subject', 'message'),
			'optional' => array('headers' => false, 'params' => '')
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
		$headers = $memory['headers']) ? $memory['headers'] : 'From: '.$mail['user'].' <'.$mail['email'].'>\r\nReply-To: '.$mail['user'].' <'.$mail['email'].'>\r\nX-Mailer: PHP/'.phpversion();
		$params = $memory['params'];
		
		if(!mail($to, $subject, $message, $headers, $params)){
			$memory['valid'] = false;
			$memory['msg'] = 'Error sending Mail';
			$memory['status'] = 503;
			$memory['details'] = 'Error : mail() returned false @mail.send.service';
			return $meory;
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