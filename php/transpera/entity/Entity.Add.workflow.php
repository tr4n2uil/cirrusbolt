<?php 
require_once(SBSERVICE);

/**
 *	@class EntityAddWorkflow
 *	@desc Adds new entity
 *
 *	@param relation string Relation name [memory]
 *	@param sqlcnd string SQL condition [memory]
 *	@param args array Query parameters [args]
 *	@param escparam array Escape parameters [memory] optional default array()
 *	@param keyid long int Usage Key ID [memory]
 *	@param user string User email [memory]
 *	@param parent long int Parent ID [memory] optional default 0
 *	@param level integer Web level [memory] optional default false (inherit parent admin access)
 *	@param authorize string Authorize control value [memory] optional default 'edit:child:list'
 *	@param owner long int Owner ID [memory] optional default keyid
 *	@param successmsg string Success message [memory] optional default 'Entity added successfully'
 *
 *	@param conn array DataService instance configuration key [memory]
 *
 *	@return id long int Entity ID [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class EntityAddWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('conn', 'keyid', 'user', 'relation', 'sqlcnd'),
			'optional' => array('parent' => 0, 'level' => false, 'authorize' => 'edit:child:list', 'owner' => false, 'escparam' => array(), 'successmsg' => 'Entity added successfully')
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['msg'] = $memory['successmsg'];
		$memory['owner'] = $memory['owner'] ? $memory['owner'] : $memory['keyid'];
		
		$workflow = array(
		array(
			'service' => 'transpera.reference.add.workflow'
		),
		array(
			'service' => 'transpera.relation.insert.workflow',
			'args' => array_merge($memory['args'], array('id', 'owner', 'user')),
			'escparam' => array_merge($memory['escparam'], array('user'))
		));
		
		return Snowblozm::execute($workflow, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('id');
	}
	
}

?>