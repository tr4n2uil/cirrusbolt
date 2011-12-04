<?php 
require_once(SBSERVICE);

/**
 *	@class WebChildrenWorkflow
 *	@desc Lists child chains of parent in the web
 *
 *	@param parent long int Chain ID [memory]
 *
 *	@return children array Children IDs [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class WebChildrenWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('parent')
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['msg'] = 'Web children listed successfully';
		
		$service = array(
			'service' => 'ad.relation.select.workflow',
			'args' => array('parent'),
			'conn' => 'adconn',
			'relation' => '`webs`',
			'sqlprj' => '`child`, `parent`, `path`, `leaf`',
			'sqlcnd' => "where `parent`=\${parent}",
			'check' => false,
			'output' => array('result' => 'children')
		);
		
		return Snowblozm::run($service, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('children');
	}
	
}

?>