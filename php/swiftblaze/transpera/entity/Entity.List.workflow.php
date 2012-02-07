<?php 
require_once(SBSERVICE);

/**
 *	@class EntityListWorkflow
 *	@desc Returns all entity information in parent
 *
 *	@param chadm boolean Is chack admin [memory] optional default true
 *	@param mgchn boolean Is merge chain [memory] optional default true
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
 *	@param acstate string State to authorize chain [memory] optional default true (false= All)
 *	@param action string Action to authorize member [memory] optional default 'list'
 *	@param astate string State to authorize member [memory] optional default true (false= None)
 *	@param iaction string Action to authorize inherit [memory] optional default 'list'
 *	@param aistate string State to authorize inherit [memory] optional default true (false= None)
 *	@param authinh integer Check inherit [memory] optional default 1
 *	@param autherror string Error msg [memory] optional default 'Unable to Authorize'
 *
 *	@param relation string Relation name [memory]
 *	@param sqlcnd string SQL condition [memory]
 *	@param sqlprj string SQL projection [memory] optional default *
 *	@param user string Username [memory] optional default 'unknown@entity.list'
 *	@param escparam array Escape params [memory] optional default array()
 *	@param successmsg string Success message [memory] optional default 'Entity information successfully'
 *	@param mapkey string Map Key [memory] optional default 0
 *	@param mapname string Map Name [memory] optional default 'entity'
 *
 *	@param sacstate string State to authorize chain [memory] optional default true (false= All)
 *	@param saction string Action to authorize [memory] optional default 'edit'
 *	@param sastate string State to authorize member [memory] optional default true (false= All)
 *	@param siaction string Action to authorize inherit [memory] optional default 'edit'
 *	@param saistate string State to authorize inherit [memory] optional default true (false= All)
 *	@param sinit boolean init flag [memory] optional default true
 *	@param sauthinh integer Check inherit [memory] optional default 1
 *	@param sautherror string Error msg [memory] optional default 'Unable to Authorize'
 *
 *	@param cache boolean Is cacheable [memory] optional default true
 *	@param expiry int Cache expiry [memory] optional default 85
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
			'required' => array('conn', 'keyid', 'id', 'relation', 'sqlcnd'),
			'optional' => array(
				'user' => 'unknown@entity.list',
				'chadm' => true,
				'mgchn' => true,
				'selection' => 'list',
				'type' => 'general', 
				'state' => true, 
				'istate' => true, 
				'pgsz' => false, 
				'pgno' => 0, 
				'total' => false,
				'acstate' => true,
				'action' => 'list', 
				'astate' => true, 
				'iaction' => 'list', 
				'aistate' => true,
				'authinh' => 1,
				'autherror' => 'Unable to Authorize',
				'sacstate' => true,
				'saction' => 'edit', 
				'sastate' => true, 
				'siaction' => 'edit', 
				'saistate' => true, 
				'sinit' => true,
				'sauthinh' => 1,
				'sautherror' => 'Unable to Authorize',
				'sqlprj' => '*', 
				'successmsg' => 'Entities information given successfully', 
				'listerror' => 'Error in Database',
				'escparam' => array(),
				'mapkey' => 0,
				'mapname' => 'entity',
				'cache' => true,
				'expiry' => 150
			)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$cache = $memory['cache'];

		if($cache){
			$poolkey = 'ENTITY_LIST'.json_encode($memory);
			$pool = Snowblozm::run(array(
				'service' => 'pool.lite.get.service',
				'key' => $poolkey
			), array());
		}
		
		if($cache && $pool['valid']){
			$memory = $pool['data'];
		} 
		else {
		
			$memory['msg'] = $memory['successmsg'];
			$memory['admin'] = 0;
			
			if(!in_array($memory['selection'], array('list', 'children', 'parents'))){
				$memory['valid'] = false;
				$memory['msg'] = 'Invalid Selection Type';
				$memory['valid'] = 500;
				$memory['valid'] = 'Invalid selection : '.$memory['selection'].' @entity.list';
				return $memory;
			}
			
			$workflow = array(
			array(
				'service' => 'transpera.reference.'.$memory['selection'].'.workflow',
			),
			array(
				'service' => 'cbcore.data.list.service',
				'args' => array($memory['selection']),
				'attr' => 'child',
				'mapname' => 'web',
				'default' => array(-1)
			),
			array(
				'service' => 'transpera.relation.select.workflow',
				'args' => array_merge($memory['args'], array('list')),
				'input' => array('errormsg' => 'listerror'),
				'escparam' => array_merge($memory['escparam'], array('list')),
				'check' => false,
				'output' => array('result' => 'entities')
			));
			
			if($memory['chadm']){
				array_push($workflow,
				array(
					'service' => 'transpera.reference.authorize.workflow',
					'input' => array(
						'acstate' => 'sacstate', 
						'action' => 'saction', 
						'astate' => 'sastate', 
						'iaction' => 'siaction', 
						'iastate' => 'siastate', 
						'init' => 'sinit',
						'authinh' => 'sauthinh',
						'autherror' => 'sautherror'
					),
					'admin' => true,
				));
			}
			
			if($memory['mgchn']){
				array_push($workflow,
				array(
					'service' => 'guard.chain.list.workflow',
					'input' => array('chainid' => 'list')
				),
				array(
					'service' => 'cbcore.data.merge.service',
					'args' => array('entities', 'chains'),
					'params' => array('entities' => array(0, $memory['mapname']), 'chains' => array(0, 'chain')),
					'output' => array('result' => 'entities')
				));
			}
			
			$memory = Snowblozm::execute($workflow, $memory);
			if($cache){
				Snowblozm::run(array(
					'service' => 'pool.lite.save.service',
					'key' => $poolkey,
					'data' => $memory
				), array());
			}
		}
		
		return $memory;
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('entities', 'id', 'admin');
	}
	
}

?>