<?php 
require_once(SBSERVICE);

/**
 *	@class DeviceAddWorkflow
 *	@desc Adds device to Key
 *
 *	@param keyid long int Key ID [memory]
 *	@param devid long int Device ID [memory]
 *	@param type string Type name [memory] optional default 'general'
 *	@param authorize string Parent control [memory] optional default 'edit:add:remove:list'
 *	@param control string Authorize control value [memory] optional default false='info:'.$authorize true=$authorize
 *	@param state string State value [memory] optional default 'A'
 *	@param path string Collation path [memory] optional default '/'
 *	@param leaf string Collation leaf [memory] optional default 'Child ID'
 *
 *	@return return id long int DeviceKey ID [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class DeviceAddWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('keyid', 'devid'),
			'optional' => array('type' => 'general', 'authorize' => 'edit:add:remove:list', 'control' => false, 'state' => 'A', 'path' => '/', 'leaf' => false)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['msg'] = 'Device added successfully';
		$memory['leaf'] = $memory['leaf'] ? $memory['leaf'] : $memory['devid'];
		$memory['control'] = $memory['control'] ? ($memory['control'] === true ? $memory['authorize'] : $memory['control']) : 'info:'.$memory['authorize'];
		
		$service = array(
			'service' => 'transpera.relation.insert.workflow',
			'args' => array('devid', 'keyid', 'type', 'control', 'state', 'path', 'leaf'),
			'conn' => 'cbconn',
			'relation' => '`devices`',
			'sqlcnd' => "(`devid`, `keyid`, `type`, `control`, `state`, `path`, `leaf`, `ctime`) values (\${devid}, \${keyid}, '\${type}', '\${control}', '\${state}', '\${path}', '\${leaf}', now())",
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