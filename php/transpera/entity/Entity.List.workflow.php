<?php 
require_once(SBSERVICE);

/**
 *	@class EntityListWorkflow
 *	@desc Returns all entity information in parent
 *
 *	@param selection string Selection operation [memory] optional default 'list' ('list', 'children', 'parents')
 *
 *	@param keyid long int Usage Key ID [memory]
 *	@param id long int Reference ID [memory]
 *	@param type string Type name [memory] optional default 'general'
 *	@param state string State [memory] optional default false (true= Not '0')
 *	@param istate string State Inherit [memory] optional default false (true= Not '0')
 *
 *	@param pgsz long int Paging Size [memory] optional default false
 *	@param pgno long int Paging Index [memory] optional default 1
 *	@param total long int Paging Total [memory] optional default false
 *
 *	@param action string Action to authorize member [memory] optional default 'list'
 *	@param astate string State to authorize member [memory] optional default true (false= None)
 *	@param iaction string Action to authorize inherit [memory] optional default 'list'
 *	@param aistate string State to authorize inherit [memory] optional default true (false= None)
 *
 *	@param relation string Relation name [memory]
 *	@param sqlcnd string SQL condition [memory]
 *	@param sqlprj string SQL projection [memory] optional default *
 *	@param user string User email [memory]
 *	@param escparam array Escape params [memory] optional default array()
 *	@param successmsg string Success message [memory] optional default 'Entity information successfully'
 *
 *	@param saction string Action to authorize [memory] optional default 'edit'
 *	@param sastate string State to authorize member [memory] optional default true (false= All)
 *	@param siaction string Action to authorize inherit [memory] optional default 'edit'
 *	@param saistate string State to authorize inherit [memory] optional default true (false= All)
 *	@param sinit boolean init flag [memory] optional default true
 *
 *	@param conn array DataService instance configuration key [memory]
 *
 *	@return entities long int Entities information [memory]
 *	@return total long int Total count [memory]
 *	@return id long int Parent ID [memory]
 *	@return admin integer Is admin [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class EntityListWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('conn', 'keyid', 'user', 'id', 'relation', 'sqlcnd'),
			'optional' => array(
				'selection' => 'list',
				'type' => 'general', 
				'state' => true, 
				'istate' => true, 
				'pgsz' => false, 
				'pgno' => 0, 
				'total' => false,
				'action' => 'list', 
				'astate' => true, 
				'iaction' => 'list', 
				'aistate' => true,
				'saction' => 'edit', 
				'sastate' => true, 
				'siaction' => 'edit', 
				'saistate' => true, 
				'sinit' => true,
				'sqlprj' => '*', 
				'successmsg' => 'Entities information given successfully', 
				'escparam' => array()
			)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['msg'] = $memory['successmsg'];
		
		if(!in_array($memory['selection'], array('list', 'children', 'parents'))){
			$memory['valid'] = false;
			$memory['msg'] = 'Invalid Selection Type';
			$memory['valid'] = 500;
			$memory['valid'] = 'Invalid selection : '.$memory['selection'].' @entity.list';
			return $memory;
		}
		
		$workflow = array(
		array(
			'service' => 'transpera.reference.'.$memory['selection'].'.workflow'
		),
		array(
			'service' => 'cbcore.data.list.service',
			'args' => array($memory['selection']),
			'attr' => 'child',
			'default' => array(-1)
		),
		array(
			'service' => 'transpera.relation.select.workflow',
			'args' => array_merge($memory['args'], array('list')),
			'escparam' => array_merge($memory['escparam'], array('list')),
			'check' => false,
			'output' => array('result' => 'entities')
		),
		array(
			'service' => 'transpera.reference.authorize.workflow',
			'input' => array('action' => 'saction', 'astate' => 'sastate', 'iaction' => 'siaction', 'iastate' => 'siastate', 'init' => 'sinit'),
			'admin' => true,
		));
		
		return Snowblozm::execute($workflow, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('entities', 'id', 'admin');
	}
	
}

?>