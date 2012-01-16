<?php 
require_once(SBSERVICE);

/**
 *	@class ReferenceCreateWorkflow
 *	@desc Manages creation of new reference 
 *
 *	@param keyid long int Usage Key ID [memory]
 *	@param parent long int Reference ID [memory]
 *	@param level integer Web level [memory] optional default 0
 *	@param email string Email [memory]
 *	@param keyvalue string Key value [memory]
 *	@param authorize string Authorize control value [memory] optional default (inherit)
 *	@param control string Authorize control value [memory] optional default false='info:'.(inherit) true=(inherit)
 *	@param state string State value [memory] optional default 'A'
 *	@param root string Collation root [memory] optional default '/masterkey'
 *	@param type string Type name [memory] optional default 'general'
 *	@param path string Collation path [memory] optional default '/'
 *	@param leaf string Collation leaf [memory] optional default 'Child ID'
 *
 *	@return return id long int Reference ID [memory]
 *	@return owner long int Owner Key ID [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class ReferenceCreateWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('keyid', 'parent', 'email', 'keyvalue'),
			'optional' => array('level' => 0, 'root' => false, 'type' => 'general', 'path' => '/', 'leaf' => false, 'authorize' => false, 'control' => false, 'state' => 'A')
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['msg'] = 'Reference created successfully';
		$level = $memory['level'] ? 'inhlevel' : 'level';
		$authorize = $memory['authorize'] ? 'inhauthorize' : 'authorize';
		
		$workflow = array(
		array(
			'service' => 'guard.key.available.workflow'
		),
		array(
			'service' => 'transpera.reference.authorize.workflow',
			'input' => array('id' => 'parent'),
			'action' => 'add',
			'output' => array('level' => $level, 'authorize' => $authorize)
		),
		array(
			'service' => 'guard.key.add.workflow',
			'input' => array('key' => 'keyvalue'),
			'output' => array('id' => 'owner')
		),
		array(
			'service' => 'guard.chain.add.workflow',
			'input' => array('masterkey' => 'owner')
		),
		array(
			'service' => 'guard.web.add.workflow',
			'input' => array('child' => 'id'),
			'output' => array('id' => 'webid')
		));
		
		return Snowblozm::execute($workflow, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('id', 'owner');
	}
	
}

?>