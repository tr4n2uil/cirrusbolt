<?php 
require_once(SBSERVICE);

/**
 *	@class EntityEditWorkflow
 *	@desc Edits entity using ID
 *
 *	@param id long int Entity ID [memory]
 *	@param relation string Relation name [memory]
 *	@param sqlcnd string SQL condition [memory]
 *	@param args array Query parameters [args]
 *	@param escparam array Escape parameters [memory] optional default array()
 *	@param keyid long int Usage Key ID [memory]
 *	@param user string User email [memory]
 *	@param successmsg string Success message [memory] optional default 'Entity edited successfully'
 *
 *	@param conn array DataService instance configuration key [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class EntityEditWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('conn', 'keyid', 'user', 'id', 'relation', 'sqlcnd'),
			'optional' => array('escparam' => array(), 'successmsg' => 'Entity edited successfully')
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['msg'] = $memory['successmsg'];
		
		$workflow = array(
		array(
			'service' => 'transpera.reference.authorize.workflow'
		),
		array(
			'service' => 'transpera.relation.update.workflow',
			'args' => $memory['args']
		),
		array(
			'service' => 'gauge.track.write.workflow'
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