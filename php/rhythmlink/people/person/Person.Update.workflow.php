<?php 
require_once(SBSERVICE);

/**
 *	@class PersonUpdateWorkflow
 *	@desc Edits person contacts:devices using ID
 *
 *	@param pnid long int Person ID [memory]
 *	@param device string Device to verify [memory] optional default 'mail' ('mail', 'sms')
 *	@param email string Person email [memory] optional default false
 *	@param phone string Person phone [memory] optional default false
 *	@param keyid long int Usage Key ID [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class PersonUpdateWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('keyid', 'pnid'),
			'optional' => array('email' => false, 'phone' => false, 'device' => 'mail')
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		switch($memory['device']){
			case 'mail' :
				$attr = 'email';
				break;
			case 'sms' : 
				$attr = 'phone';
				break;
			default :
				$memory['valid'] = false;
				$memory['msg'] = 'Invalid Type';
				$memory['status'] = 500;
				$memory['details'] = "Person device type : ".$type." is invalid @people.person.update";
				return $memory;
				break;
		}
	
		$workflow = array(
		array(
			'service' => 'transpera.entity.edit.workflow',
			'args' => array($attr),
			'input' => array('id' => 'pnid'),
			'conn' => 'rlconn',
			'relation' => '`persons`',
			'sqlcnd' => "set `$attr`='\${".$attr."}' where `pnid`=\${id}",
			'escparam' => array($attr),
			'successmsg' => 'Person updated successfully',
			'errormsg' => 'No Change / Invalid Person ID'
		),
		array(
			'service' => 'transpera.relation.update.workflow',
			'args' => array('owner', 'device'),
			'conn' => 'rlconn',
			'relation' => '`persons`',
			'sqlcnd' => "set `verify`='', `device`='\${device}' where `owner`=\${owner}",
			'escparam' => array('device'),
			'errormsg' => 'Invalid Person'
		),
		array(
			'service' => 'invoke.interface.session.workflow'
		));
		
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