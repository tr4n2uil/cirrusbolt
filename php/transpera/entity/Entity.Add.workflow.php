<?php 
require_once(SBSERVICE);

/**
 *	@class EntityAddWorkflow
 *	@desc Adds new entity
 *
 *	@param keyid long int Usage Key ID [memory]
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
 *	@param action string Action to authorize member [memory] optional default 'add'
 *	@param astate string State to authorize member [memory] optional default true (false= None)
 *	@param iaction string Action to authorize inherit [memory] optional default 'add'
 *	@param aistate string State to authorize inherit [memory] optional default true (false= None)
 *
 *	@param construct array Construction Workflow [memory] optional default false
 *	@param cparam array Construction Parameters [memory] optional default array()
 *
 *	@param user string User email [memory] optional default unknown@entity.add
 *	@param relation string Relation name [memory]
 *	@param owner long int Owner ID [memory] optional default keyid
 *	@param sqlcnd string SQL condition [memory]
 *	@param args array Query parameters [args]
 *	@param escparam array Escape parameters [memory] optional default array()
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
			'required' => array('conn', 'keyid', 'parent', 'relation', 'sqlcnd'),
			'optional' => array(
				'user' => 'unknown@entity.add',
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
				'istate' => 'A',
				'action' => 'add', 
				'astate' => true, 
				'iaction' => 'add', 
				'aistate' => true,
				'escparam' => array(), 
				'successmsg' => 'Entity added successfully', 
				'construct' => false,
				'cparam' => array()
			)
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
		));
		
		if($memory['construct']){
			foreach($memory['construct'] as $construct)
				array_push($workflow, $construct);
		}
		
		array_push($workflow,
		array(
			'service' => 'transpera.relation.insert.workflow',
			'args' => array_merge($memory['args'], array('id', 'owner', 'user'), $memory['cparam']),
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