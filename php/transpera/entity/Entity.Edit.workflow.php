<?php 
require_once(SBSERVICE);

/**
 *	@class EntityEditWorkflow
 *	@desc Edits entity using ID
 *
 *	@param keyid long int Usage Key ID [memory]
 *	@param id long int Reference ID [memory]
 *	@param acstate string State to authorize chain [memory] optional default true (false= All)
 *	@param action string Action to authorize [memory] optional default 'edit'
 *	@param astate string State to authorize member [memory] optional default true (false= All)
 *	@param iaction string Action to authorize inherit [memory] optional default 'edit'
 *	@param aistate string State to authorize inherit [memory] optional default true (false= All)
 *	@param init boolean init flag [memory] optional default true
 *
 *	@param user string User email [memory]
 *	@param relation string Relation name [memory]
 *	@param sqlcnd string SQL condition [memory]
 *	@param args array Query parameters [args]
 *	@param escparam array Escape parameters [memory] optional default array()
 *	@param successmsg string Success message [memory] optional default 'Entity edited successfully'
 *
 *	@param cache boolean Is cacheable [memory] optional default true
 *	@param expiry int Cache expiry [memory] optional default 150
 *
 *	@param conn array DataService instance configuration key [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class EntityEditWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('conn', 'keyid', 'user', 'id', 'relation', 'sqlcnd'),
			'optional' => array(
				'acstate' => true,
				'action' => 'edit', 
				'astate' => true, 
				'iaction' => 'edit', 
				'aistate' => true, 
				'init' => true,
				'escparam' => array(), 
				'successmsg' => 'Entity edited successfully',
				'cache' => true,
				'expiry' => 150
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
		),
		array(
			'service' => 'transpera.relation.update.workflow',
			'args' => array_merge($memory['args'], array('id'))
		),
		array(
			'service' => 'gauge.track.write.workflow'
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