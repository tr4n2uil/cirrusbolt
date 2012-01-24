<?php 
require_once(SBSERVICE);

/**
 *	@class ReferenceAuthorizeWorkflow
 *	@desc Manages authorization of existing reference 
 *
 *	@param keyid long int Usage Key ID [memory]
 *	@param id long int Reference ID [memory]
 *	@param action string Action to authorize [memory] optional default 'edit'
 *	@param acstate string State to authorize chain [memory] optional default true (false= All)
 *	@param astate string State to authorize member [memory] optional default true (false= All)
 *	@param iaction string Action to authorize inherit [memory] optional default 'edit'
 *	@param aistate string State to authorize inherit [memory] optional default true (false= All)
 *	@param init boolean init flag [memory] optional default true
 *	@param admin boolean Is return admin flag [memory] optional default false
 *
 *	@return masterkey long int Master key ID [memory]
 *	@return admin boolean Is admin [memory]
 *	@return level integer Level [memory]
 *	@return authorize string Authorization Control [memory]
 *	@return state string State [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class ReferenceAuthorizeWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('keyid', 'id'),
			'optional' => array(
				'acstate' => true, 
				'action' => 'edit', 
				'astate' => true, 
				'iaction' => 'edit', 
				'aistate' => true, 
				'admin' => false, 
				'init' => true
			)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['msg'] = 'Reference authorized successfully';
		
		if($memory['keyid'] === false){
			$memory['masterkey'] = $memory['admin'] = $memory['level'] = 0;
			$memory['authorize'] = 'edit:add:remove:list';
			$memory['valid'] = true;
			$memory['status'] = 200;
			$memory['details'] = 'Successfully executed';
			return $memory;
		}
		
		$service = array(
			'service' => 'guard.chain.authorize.workflow',
			'input' => array('chainid' => 'id', 'cstate' => 'acstate', 'state' => 'astate', 'istate' => 'aistate')
		);
		
		return Snowblozm::run($service, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('masterkey', 'admin', 'level', 'authorize', 'state');
	}
	
}

?>