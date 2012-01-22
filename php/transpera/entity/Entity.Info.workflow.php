<?php 
require_once(SBSERVICE);

/**
 *	@class EntityInfoWorkflow
 *	@desc Returns entity information by ID
 *
 *	@param track boolean Is track [memory] optional default true
 *	@param chadm boolean Is chack admin [memory] optional default true
 *	@param mgchn boolean Is merge chain [memory] optional default true
 *
 *	@param id long int Entity ID [memory]
 *	@param user string User email [memory] optional default 'unknown@entity.info'
 *	@param relation string Relation name [memory]
 *	@param sqlcnd string SQL condition [memory]
 *	@param sqlprj string SQL projection [memory] optional default *
 *	@param errormsg string Error message [memory] optional default 'Invalid Entity ID'
 *	@param successmsg string Success message [memory] optional default 'Entity information successfully'
 *
 *	@param keyid long int Usage Key ID [memory]
 *	@param parent long int Parent ID [memory] optional default 0
 *
 *	@param action string Action to authorize [memory] optional default 'edit'
 *	@param astate string State to authorize member [memory] optional default true (false= All)
 *	@param iaction string Action to authorize inherit [memory] optional default 'edit'
 *	@param aistate string State to authorize inherit [memory] optional default true (false= All)
 *	@param init boolean init flag [memory] optional default true
 *
 *	@param saction string Action to authorize [memory] optional default 'edit'
 *	@param sastate string State to authorize member [memory] optional default true (false= All)
 *	@param siaction string Action to authorize inherit [memory] optional default 'edit'
 *	@param saistate string State to authorize inherit [memory] optional default true (false= All)
 *	@param sinit boolean init flag [memory] optional default true
 *
 *	@param conn array DataService instance configuration key [memory]
 *
 *	@return entity long int Entity information [memory]
 *	@return parent long int Parent ID [memory]
 *	@return admin integer Is admin [memory]
 *	@return authorize string Authorize [memory]
 *	@return state string State [memory]
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
			'required' => array('conn', 'keyid', 'id', 'relation', 'sqlcnd'),
			'optional' => array(
				'track' => true,
				'chadm' => true,
				'mgchn' => true,
				'user' => 'unknown@entity.add',
				'parent' => 0, 
				'sqlprj' => '*', 
				'successmsg' => 'Entity information given successfully', 
				'errormsg' => 'Invalid Entity ID',
				'action' => 'info', 
				'astate' => true, 
				'iaction' => 'info', 
				'aistate' => true, 
				'init' => true,
				'saction' => 'edit', 
				'sastate' => true, 
				'siaction' => 'edit', 
				'saistate' => true, 
				'sinit' => true
			)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['msg'] = $memory['successmsg'];
		$memory['admin'] = 0;
		$memory['authorize'] = '';
		$memory['state'] = '';
		
		$workflow = array(
		array(
			'service' => 'transpera.reference.authorize.workflow'
		),
		array(
			'service' => 'transpera.relation.unique.workflow',
			'args' => array_merge(array('id'), $memory['args'])
		),
		array(
			'service' => 'cbcore.data.select.service',
			'args' => array('result'),
			'params' => array('result.0' => 'entity')
		));
		
		if($memory['track']){
			array_push($workflow,
			array(
				'service' => 'gauge.track.read.workflow',
			));
		}
		
		if($memory['chadm']){
			array_push($workflow,
			array(
				'service' => 'transpera.reference.authorize.workflow',
				//'input' => array('id' => 'parent'),
				'input' => array('action' => 'saction', 'astate' => 'sastate', 'iaction' => 'siaction', 'iastate' => 'siastate', 'init' => 'sinit'),
				'admin' => true
			));
		}
		
		if($memory['mgchn']){
			array_push($workflow,
			array(
				'service' => 'guard.chain.info.workflow',
				'input' => array('chainid' => 'id')
			));
		}
		
		return Snowblozm::execute($workflow, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('entity', 'parent', 'admin', 'authorize', 'state');
	}
	
}

?>