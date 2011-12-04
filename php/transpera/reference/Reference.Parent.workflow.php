<?php 
require_once(SBSERVICE);

/**
 *	@class ReferenceParentWorkflow
 *	@desc Manages parent finding of existing reference 
 *
 *	@param keyid long int Usage Key ID [memory]
 *	@param id long int Reference ID [memory]
 *
 *	@return web array Web member information [memory]
 *	@return parent long int Chain ID [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class ReferenceParentWorkflow implements Service {
	
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
		$memory['msg'] = 'Reference parent returned successfully';
		
		$workflow = array(
		array(
			'service' => 'ad.reference.authorize.workflow',
			'action' => 'list'
		),
		array(
			'service' => 'ad.web.parent.workflow',
			'input' => array('child' => 'id')
		));
		
		return Snowblozm::execute($workflow, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('parent', 'web');
	}
	
}

?>