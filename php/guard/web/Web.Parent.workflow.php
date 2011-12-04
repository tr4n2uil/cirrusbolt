<?php 
require_once(SBSERVICE);

/**
 *	@class WebParentWorkflow
 *	@desc Returns unique parent chain of child in the web
 *
 *	@param child long int Chain ID [memory]
 *
 *	@return web array Web member information [memory]
 *	@return parent long int Chain ID [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class WebParentWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('child')
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['msg'] = 'Web parent given successfully';
		
		$workflow = array(
		array(
			'service' => 'transpera.relation.unique.workflow',
			'args' => array('child'),
			'conn' => 'adconn',
			'relation' => '`webs`',
			'sqlcnd' => "where `child`=\${child}",
			'errormsg' => 'Unable to find unique parent'
		),
		array(
			'service' => 'cbcore.data.select.service',
			'args' => array('result'),
			'params' => array('result.0' => 'web', 'result.0.parent' => 'parent')
		));
		
		return Snowblozm::execute($workflow, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('web', 'parent');
	}
	
}

?>