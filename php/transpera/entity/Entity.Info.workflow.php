<?php 
require_once(SBSERVICE);

/**
 *	@class EntityInfoWorkflow
 *	@desc Returns entity information by ID
 *
 *	@param id long int Entity ID [memory]
 *	@param relation string Relation name [memory]
 *	@param sqlcnd string SQL condition [memory]
 *	@param sqlprj string SQL projection [memory] optional default *
 *	@param keyid long int Usage Key ID [memory]
 *	@param user string User email [memory]
 *	@param parent long int Parent ID [memory] optional default 0
 *	@param pname long int Parent name [memory] optional default ''
 *	@param action string Action to authorize [memory] optional default 'edit'
 *	@param errormsg string Error message [memory] optional default 'Invalid Entity ID'
 *	@param successmsg string Success message [memory] optional default 'Entity information successfully'
 *
 *	@param conn array DataService instance configuration key [memory]
 *
 *	@return entity long int Entity information [memory]
 *	@return pname string Parent name [memory]
 *	@return parent long int Parent ID [memory]
 *	@return admin integer Is admin [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class EntityInfoWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('conn', 'keyid', 'user', 'id', 'relation', 'sqlcnd'),
			'optional' => array('parent' => 0, 'pname' => '', 'action' => 'edit', 'sqlprj' => '*', 'successmsg' => 'Entity information given successfully', 'errormsg' => 'Invalid Entity ID')
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['msg'] = $memory['successmsg'];
		
		$workflow = array(
		array(
			'service' => 'transpera.relation.unique.workflow',
			'args' => array('id')
		),
		array(
			'service' => 'cbcore.data.select.service',
			'args' => array('result'),
			'params' => array('result.0' => 'entity')
		),
		array(
			'service' => 'gauge.track.read.workflow',
		),
		array(
			'service' => 'transpera.reference.authorize.workflow',
			'input' => array('id' => 'parent'),
			'admin' => true
		));
		
		return Snowblozm::execute($workflow, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('entity', 'pname', 'parent', 'admin');
	}
	
}

?>