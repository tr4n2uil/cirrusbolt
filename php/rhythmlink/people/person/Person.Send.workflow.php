<?php 
require_once(SBSERVICE);
require_once(CBQUEUECONF);

/**
 *	@class PersonSendWorkflow
 *	@desc Sends verification key for person by ID
 *
 *	@param username string Username [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class PersonSendWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('username')
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['msg'] = 'Verification Sent Successfully';
		//Snowblozm::$debug = true;
		$workflow = array(
		array(
			'service' => 'transpera.relation.unique.workflow',
			'args' => array('username'),
			'conn' => 'rlconn',
			'relation' => '`persons`',
			'sqlcnd' => "where `username`='\${username}' and `device`<>''",
			'escparam' => array('username'),
			'errormsg' => 'Invalid Username / Nothing to Verify'
		),
		array(
			'service' => 'cbcore.data.select.service',
			'args' => array('result'),
			'params' => array('result.0.pnid' => 'pnid', 'result.0.owner' => 'keyid', 'result.0.device' => 'device', 'result.0.email' => 'email', 'result.0.phone' => 'phone')
		),
		array(
			'service' => 'cbcore.random.string.service',
			'length' => 8,
			'output' => array('random' => 'verify')
		),
		array(
			'service' => 'transpera.relation.update.workflow',
			'args' => array('pnid', 'verify'),
			'conn' => 'rlconn',
			'relation' => '`persons`',
			'sqlcnd' => "set `verify`='\${verify}' where `pnid`=\${pnid}",
			'escparam' => array('verify'),
			'errormsg' => 'Invalid Person ID'
		));
		
		$memory = Snowblozm::execute($workflow, $memory);
		if(!$memory['valid'])
			return $memory;
		
		switch($memory['device']){
			case 'mail' :
				$workflow = array(
				array(
					'service' => 'cbcore.data.substitute.service',
					'args' => array('username', 'verify', 'email'),
					'data' => PERSON_SEND_MAIL_BODY,
					'output' => array('result' => 'body')
				),
				array(
					'service' => 'queue.mail.add.workflow',
					'input' => array('queid' => 'pnid', 'to' => 'email', 'user' => 'username'),
					'subject' => PERSON_SEND_MAIL_SUBJECT
				), 
				array(
					'service' => 'queue.mail.send.workflow',
					'input' => array('queid' => 'pnid')
				));
				break;
			
			case 'sms' :
				$workflow = array(
				array(
					'service' => 'cbcore.data.substitute.service',
					'args' => array('username', 'verify', 'phone'),
					'data' => PERSON_SEND_SMS_BODY,
					'output' => array('result' => 'body')
				),
				array(
					'service' => 'queue.sms.add.workflow',
					'input' => array('queid' => 'pnid', 'to' => 'phone', 'user' => 'username'),
					'from' => PERSON_SEND_SMS_FROM
				), 
				array(
					'service' => 'queue.sms.send.workflow',
					'input' => array('queid' => 'pnid')
				));
				break;
			
			default :
				$memory['valid'] = false;
				$memory['msg'] = 'Invalid Type';
				$memory['status'] = 500;
				$memory['details'] = "Person verification type : ".$type." is invalid @people.person.send";
				return $memory;
				break;
		}

		return Snowblozm::execute($workflow, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array();
	}
	
}

?>