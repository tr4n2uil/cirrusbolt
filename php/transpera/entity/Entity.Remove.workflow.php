<?php 
require_once(SBSERVICE);

/**
 *	@class EntityRemoveWorkflow
 *	@desc Removes entity by ID
 *
 *	@param id long int Entity ID [memory]
 *	@param relation string Relation name [memory]
 *	@param sqlcnd string SQL condition [memory]
 *	@param keyid long int Usage Key ID [memory]
 *	@param user string User email [memory]
 *	@param parent long int Parent ID [memory] optional default 0
 *	@param pname long int Parent name [memory] optional default ''
 *	@param errormsg string Error message [memory] optional default 'Invalid Entity ID'
 *	@param successmsg string Success message [memory] optional default 'Entity information successfully'
 *	@param destruct array Destruction Workflow [memory] optional default false
 *
 *	@param conn array DataService instance configuration key [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class EntityRemoveWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('conn', 'keyid', 'user', 'id', 'relation', 'sqlcnd'),
			'optional' => array('parent' => 0, 'successmsg' => 'Entity removed successfully', 'errormsg' => 'Invalid Entity ID', 'destruct' => false)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['msg'] = $memory['successmsg'];
		
		$workflow = array(
		array(
			'service' => 'transpera.reference.authorize.workflow',
			'action' => 'remove'
		));
		
		if($memory['destruct']){
			$workflow = array_push($workflow, $memory['destruct']);
		}
		
		$workflow = array_push($workflow,
		array(
			'service' => 'transpera.reference.remove.workflow'
		),
		array(
			'service' => 'transpera.relation.delete.workflow',
			'args' => array('id')
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