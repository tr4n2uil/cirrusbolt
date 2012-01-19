<?php 
require_once(SBSERVICE);

/**
 *	@class ReferenceAddWorkflow
 *	@desc Manages addition of new reference 
 *
 *	@param keyid long int Usage Key ID [memory] optional default false
 *	@param parent long int Reference ID [memory]
 *	@param level integer Web level [memory] optional default (inherit)
 *	@param owner long int Owner Key ID [memory] optional default keyid
 *	@param authorize string Authorize control value [memory] optional default (inherit)
 *	@param control string Authorize control value for member [memory] optional default false='info:'.(inherit) true=(inherit)
 *	@param icontrol string Authorize control value for web [memory] optional default false='info:'.(inherit) true=(inherit)
 *	@param inherit integer Is inherit [memory] optional default 1
 *	@param state string State value for member [memory] optional default 'A'
 *	@param istate string State value for web [memory] optional default 'A'
 *	@param root string Collation root [memory] optional default '/masterkey'
 *	@param type string Type name [memory] optional default 'general'
 *	@param path string Collation path [memory] optional default '/'
 *	@param leaf string Collation leaf [memory] optional default 'Child ID'
 *
 *	@return return id long int Reference ID [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class ReferenceAddWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('keyid', 'parent'),
			'optional' => array(
				'level' => false, 
				'owner' => false, 
				'root' => false, 
				'type' => 'general', 
				'path' => '/', 
				'leaf' => false, 
				'authorize' => false, 
				'inherit' => 1, 
				'control' => false, 
				'state' => 'A', 
				'icontrol' => false, 
				'istate' => 'A'
			)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['owner'] = $memory['owner'] ? $memory['owner'] : $memory['keyid'];
		$memory['msg'] = 'Reference added successfully';
		$level = $memory['level'] ? 'inhlevel' : 'level';
		$authorize = $memory['authorize'] ? 'inhauthorize' : 'authorize';
		
		$workflow = array(
		array(
			'service' => 'transpera.reference.authorize.workflow',
			'input' => array('id' => 'parent'),
			'action' => 'add',
			'output' => array('level' => $level, 'authorize' => $authorize)
		),
		array(
			'service' => 'guard.chain.add.workflow',
			'input' => array('masterkey' => 'owner')
		),
		array(
			'service' => 'guard.member.add.workflow',
			'input' => array('keyid' => 'owner', 'chainid' => 'id'),
			'output' => array('id' => 'memid')
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
		return array('id');
	}
	
}

?>