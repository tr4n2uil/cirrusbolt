<?php 
require_once(SBSERVICE);

/**
 *	@class ReferenceChildrenWorkflow
 *	@desc Manages children listing of existing reference 
 *
 *	@param keyid long int Usage Key ID [memory]
 *	@param id long int Reference ID [memory]
 *
 *	@return children array Chain children information [memory]
 *	@return level integer Parent Authorization Level [memory]
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
			'required' => array('keyid', 'id')
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['msg'] = 'Reference children listed successfully';
		
		$workflow = array(
		array(
			'service' => 'transpera.reference.authorize.workflow',
			'action' => 'list'
		),
		array(
			'service' => 'guard.web.children.workflow',
			'input' => array('parent' => 'id')
		));
		
		return Snowblozm::execute($workflow, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('children', 'level');
	}
	
}

?>