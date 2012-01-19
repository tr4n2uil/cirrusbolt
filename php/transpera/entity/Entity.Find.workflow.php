<?php 
require_once(SBSERVICE);

/**
 *	@class EntityFindWorkflow
 *	@desc Returns entity information by email
 *
 *	@param idkey string ID Key [memory]
 *	@param relation string Relation name [memory]
 *	@param sqlcnd string SQL condition [memory]
 *	@param sqlprj string SQL projection [memory] optional default *
 *	@param keyid long int Usage Key ID [memory]
 *	@param user string User email [memory] optional default 'unknown@entity.info'
 *	@param parent long int Parent ID [memory] optional default 0
 *	@param action string Action to authorize [memory] optional default 'edit'
 *	@param errormsg string Error message [memory] optional default 'Invalid Entity ID'
 *	@param successmsg string Success message [memory] optional default 'Entity information successfully'
 *
 *	@param conn array DataService instance configuration key [memory]
 *
 *	@param id long int Entity ID [memory]
 *	@return entity long int Entity information [memory]
 *	@return parent long int Parent ID [memory]
 *	@return admin integer Is admin [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class EntityFindWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('conn', 'keyid', 'idkey', 'relation', 'sqlcnd'),
			'optional' => array(
				'user' => 'unknown@entity.add',
				'parent' => 0, 
				'action' => 'edit', 
				'sqlprj' => '*', 
				'successmsg' => 'Entity information given successfully', 
				'errormsg' => 'Invalid Entity ID'
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
			'service' => 'transpera.relation.unique.workflow',
			'args' => $memory['args']
		),
		array(
			'service' => 'cbcore.data.select.service',
			'args' => array('result'),
			'params' => array('result.0' => 'entity', 'result.0.'.$memory['idkey'] => 'id')
		),
		array(
			'service' => 'transpera.reference.authorize.workflow',
			'action' => 'info'
		),
		array(
			'service' => 'gauge.track.read.workflow',
		),
		array(
			'service' => 'transpera.reference.authorize.workflow',
			'admin' => true
		));
		
		return Snowblozm::execute($workflow, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('entity', 'parent', 'admin', 'id');
	}
	
}

?>