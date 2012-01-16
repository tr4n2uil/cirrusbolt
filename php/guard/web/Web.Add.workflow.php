<?php 
require_once(SBSERVICE);

/**
 *	@class WebAddWorkflow
 *	@desc Adds child chain to parent in the web
 *
 *	@param child long int Chain ID [memory]
 *	@param parent long int Chain ID [memory]
 *	@param type string Type name [memory] optional default 'general'
 *	@param authorize string Parent control [memory] optional default 'edit:add:remove:list'
 *	@param control string Authorize control value [memory] optional default false='info:'.$authorize true=$authorize
 *	@param state string State value [memory] optional default 'A'
 *	@param path string Collation path [memory] optional default '/'
 *	@param leaf string Collation leaf [memory] optional default 'Child ID'
 *
 *	@return id long int Web member ID [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class WebAddWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('child', 'parent'),
			'optional' => array('type' => 'general', 'authorize' => 'edit:add:remove:list', 'control' => false, 'state' => 'A', 'path' => '/', 'leaf' => false)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['msg'] = 'Web member added successfully';
		$memory['leaf'] = $memory['leaf'] ? $memory['leaf'] : $memory['child'];
		$memory['control'] = $memory['control'] ? ($memory['control'] === true ? $memory['authorize'] : $memory['control']) : 'info:'.$memory['authorize'];
		
		$service = array(
			'service' => 'transpera.relation.insert.workflow',
			'args' => array('child', 'parent', 'type', 'control', 'state', 'path', 'leaf'),
			'conn' => 'cbconn',
			'relation' => '`webs`',
			'sqlcnd' => "(`child`, `parent`, `type`, `control`, `state`, `path`, `leaf`) values (\${child}, \${parent}, '\${type}', '\${control}', '\${state}', '\${path}', '\${leaf}')",
			'escparam' => array('type', 'control', 'state', 'path', 'leaf')
		);
		
		return Snowblozm::run($service, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('id');
	}
	
}

?>