<?php 
require_once(SBSERVICE);

/**
 *	@class DeviceRemoveWorkflow
 *	@desc Removes device from key
 *
 *	@param keyid long int Key ID [memory]
 *	@param devid long int Device ID [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class DeviceRemoveWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('keyid', 'devid')
		);
	}

	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['msg'] = 'Device removed successfully';
		
		$service = array(
			'service' => 'transpera.relation.delete.workflow',
			'args' => array('keyid', 'devid'),
			'conn' => 'cbconn',
			'relation' => '`devices`',
			'sqlcnd' => "where `devid`=\${devid} and `keyid`=\${keyid}",
			'errormsg' => 'Invalid Device'
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