<?php 
require_once(SBSERVICE);

/**
 *	@class ReferenceMemberWorkflow
 *	@desc Manages member listing of existing reference 
 *
 *	@param keyid long int Usage Key ID [memory]
 *	@param id long int Reference ID [memory]
 *	@param state string State [memory] optional default false (true= Not '0')
 *
 *	@param pgsz long int Paging Size [memory] optional default false
 *	@param pgno long int Paging Index [memory] optional default 1
 *	@param total long int Paging Total [memory] optional default false
 *
 *	@param action string Action to authorize member [memory] optional default 'edit'
 *	@param astate string State to authorize member [memory] optional default true (false= None)
 *	@param iaction string Action to authorize inherit [memory] optional default 'edit'
 *	@param aistate string State to authorize inherit [memory] optional default true (false= None)
 *
 *	@return members array Members information [memory]
 *	@return total long int Paging total [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class ReferenceMemberWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('keyid', 'id'),
			'optional' => array(
				'state' => false, 
				'pgsz' => false, 
				'pgno' => 0, 
				'total' => false,
				'action' => 'edit', 
				'astate' => true, 
				'iaction' => 'edit', 
				'aistate' => true
			)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['msg'] = 'Reference member keys listed successfully';
		
		$workflow = array(
		array(
			'service' => 'transpera.reference.authorize.workflow'
		),
		array(
			'service' => 'guard.chain.member.workflow',
			'input' => array('chainid' => 'id'),
			'output' => array('result' => 'members')
		));
		
		return Snowblozm::execute($workflow, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('members', 'total');
	}
	
}

?>