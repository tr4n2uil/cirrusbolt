<?php 
require_once(SBSERVICE);

/**
 *	@class ReferenceChildrenWorkflow
 *	@desc Manages children listing of existing reference 
 *
 *	@param keyid long int Usage Key ID [memory]
 *	@param id long int Reference ID [memory]
 *	@param type string Type name [memory] optional default 'general'
 *	@param istate string State [memory] optional default false (true= Not '0')
 *
 *	@param pgsz long int Paging Size [memory] optional default false
 *	@param pgno long int Paging Index [memory] optional default 1
 *	@param total long int Paging Total [memory] optional default false
 *
 *	@param acstate string State to authorize chain [memory] optional default true (false= All)
 *	@param action string Action to authorize member [memory] optional default 'list'
 *	@param astate string State to authorize member [memory] optional default true (false= All)
 *	@param iaction string Action to authorize inherit [memory] optional default 'list'
 *	@param aistate string State to authorize inherit [memory] optional default true (false= All)
 *
 *	@param arucache boolean Is cacheable [memory] optional default true
 *	@param aruexpiry int Cache expiry [memory] optional default 150
 *	@param asrucache boolean Is cacheable [memory] optional default true
 *	@param asruexpiry int Cache expiry [memory] optional default 150
 *
 *	@param wrstcache boolean Is cacheable [memory] optional default false
 *	@param wrstexpiry int Cache expiry [memory] optional default 150
 *	@param wrscache boolean Is cacheable [memory] optional default false
 *	@param wrsexpiry int Cache expiry [memory] optional default 85
 *
 *	@return children array Chain children information [memory]
 *	@return level integer Parent Authorization Level [memory]
 *	@return total long int Paging total [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class ReferenceChildrenWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('keyid', 'id'),
			'optional' => array(
				'type' => 'general', 
				'istate' => true, 
				'pgsz' => false, 
				'pgno' => 0, 
				'total' => false,
				'acstate' => true,
				'action' => 'list', 
				'astate' => true, 
				'iaction' => 'list', 
				'aistate' => true,
				'arucache' => true,
				'aruexpiry' => 150,
				'asrucache' => true,
				'asruexpiry' => 150,
				'wrscache' => false, 
				'wrsexpiry' => 85,
				'wrstcache' => false, 
				'wrstexpiry' => 150
			)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['msg'] = 'Reference children listed successfully';
		
		$workflow = array(
		array(
			'service' => 'transpera.reference.authorize.workflow'
		),
		array(
			'service' => 'guard.web.children.workflow',
			'input' => array(
				'parent' => 'id',
				'rucache' => 'wrucache',
				'ruexpiry' => 'wruexpiry',
				'srucache' => 'wrucache',
				'sruexpiry' => 'wsruexpiry'
			)
		));
		
		return Snowblozm::execute($workflow, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('children', 'level', 'total');
	}
	
}

?>