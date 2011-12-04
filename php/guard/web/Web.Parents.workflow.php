<?php 
require_once(SBSERVICE);

/**
 *	@class WebParentsWorkflow
 *	@desc Lists parent chains of child in the web
 *
 *	@param child long int Chain ID [memory]
 *
 *	@return parents array Parents IDs [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class WebParentsWorkflow implements Service {
	
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
		$memory['msg'] = 'Web parents listed successfully';
		
		$service = array(
			'service' => 'transpera.relation.select.workflow',
			'args' => array('child'),
			'conn' => 'cbconn',
			'relation' => '`webs`',
			'sqlcnd' => "where `child`=\${child}",
			'output' => array('result' => 'parents')
		);
		
		return Snowblozm::run($service, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('parents');
	}
	
}

?>