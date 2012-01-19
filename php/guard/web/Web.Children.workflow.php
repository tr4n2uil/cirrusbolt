<?php 
require_once(SBSERVICE);

/**
 *	@class WebChildrenWorkflow
 *	@desc Lists child chains of parent in the web
 *
 *	@param parent long int Chain ID [memory]
 *	@param type string Type name [memory] optional default 'general'
 *	@param pgsz long int Paging Size [memory] optional default false
 *	@param pgno long int Paging Index [memory] optional default 1
 *	@param total long int Paging Total [memory] optional default false
 *
 *	@return children array Children IDs [memory]
 *	@return total long int Paging total [memory]
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
			'required' => array('parent'),
			'optional' => array('type' => 'general', 'pgsz' => false, 'pgno' => 0, 'total' => false)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['msg'] = 'Web children listed successfully';
		
		$service = array(
			'service' => 'transpera.relation.select.workflow',
			'args' => array('parent', 'type'),
			'conn' => 'cbconn',
			'relation' => '`webs`',
			'sqlprj' => '`child`, `parent`, `path`, `leaf`',
			'sqlcnd' => "where `parent`=\${parent} and `type`='\${type}'",
			'escparam' => array('type'),
			'check' => false,
			'output' => array('result' => 'children')
		);
		
		return Snowblozm::run($service, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('children', 'total');
	}
	
}

?>