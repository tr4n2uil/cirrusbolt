<?php 
require_once(SBSERVICE);

/**
 *	@class EntityRemoveWorkflow
 *	@desc Removes entity by ID
 *
 *	@param relation string Relation name [memory]
 *	@param sqlcnd string SQL condition [memory]
 *	@param user string User email [memory]
 *	@param errormsg string Error message [memory] optional default 'Invalid Entity ID'
 *	@param successmsg string Success message [memory] optional default 'Entity information successfully'
 *
 *	@param keyid long int Usage Key ID [memory]
 *	@param id long int Reference ID [memory]
 *	@param parent long int Reference ID [memory]
 *	@param type string Type name [memory] optional default 'general'
 *
 *	@param action string Action to authorize member [memory] optional default 'remove'
 *	@param astate string State to authorize member [memory] optional default true (false= None)
 *	@param iaction string Action to authorize inherit [memory] optional default 'remove'
 *	@param aistate string State to authorize inherit [memory] optional default true (false= None)
 *
 *	@param destruct array Destruction Workflow [memory] optional default false
 *
 *	@param saction string Action to authorize [memory] optional default 'edit'
 *	@param sastate string State to authorize member [memory] optional default true (false= All)
 *	@param siaction string Action to authorize inherit [memory] optional default 'edit'
 *	@param saistate string State to authorize inherit [memory] optional default true (false= All)
 *	@param sinit boolean init flag [memory] optional default true
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
			'optional' => array(
				'parent' => 0, 
				'type' => 'general',
				'action' => 'remove', 
				'astate' => true, 
				'iaction' => 'remove', 
				'aistate' => true
				'saction' => 'remove', 
				'sastate' => true, 
				'siaction' => 'remove', 
				'saistate' => true, 
				'sinit' => true
				'successmsg' => 'Entity removed successfully', 
				'errormsg' => 'Invalid Entity ID', 
				'destruct' => false
			)
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
		));
		
		if($memory['destruct']){
			$workflow = array_push($workflow, $memory['destruct']);
		}
		
		$workflow = array_push($workflow,
		array(
			'service' => 'transpera.reference.remove.workflow',
			'input' => array('action' => 'saction', 'astate' => 'sastate', 'iaction' => 'siaction', 'iastate' => 'siastate', 'init' => 'sinit'),
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