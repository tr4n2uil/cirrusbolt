<?php 
require_once(SBSERVICE);

/**
 *	@class DeviceDeleteWorkflow
 *	@desc Removes device from Key
 *
 *	@param keyid long int Key ID [memory]
 *	@param devid long int Device ID [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class DeviceDeleteWorkflow implements Service {
	
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
		$memory['msg'] = 'Devices removed successfully';
		
		$service = array(
			'service' => 'transpera.relation.delete.workflow',
			'args' => array('keyid', 'devid'),
			'conn' => 'cbconn',
			'relation' => '`devices`',
			'sqlcnd' => "where `devid`=\${devid}",
			'errormsg' => 'Invalid Device ID'
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