<?php 
require_once(SBSERVICE);

/**
 *	@class DeviceStateWorkflow
 *	@desc Edits device state control value
 *
 	@param keyid long int Key ID [memory]
 *	@param devid long int Device ID [memory]
 *	@param state string State value [memory] optional default '0'
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class DeviceStateWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('keyid', 'devid'),
			'optional' => array('state' => '0')
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['msg'] = 'Device state value edited successfully';
		
		$service = array(
			'service' => 'transpera.relation.update.workflow',
			'args' => array('keyid', 'devid', 'state'),
			'conn' => 'cbconn',
			'relation' => '`devices`',
			'sqlcnd' => "set `state`='\${state}' where `devid`=\${devid} and `keyid`=\${keyid}",
			'errormsg' => 'Invalid Device ID',
			'escparam' => array('state')
		);
		
		return Snowblozm::run($service, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array();
	}
	
}

?>