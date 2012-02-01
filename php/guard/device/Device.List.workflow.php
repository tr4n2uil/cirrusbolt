<?php 
require_once(SBSERVICE);

/**
 *	@class DeviceListWorkflow
 *	@desc Returns device IDs in key
 *
 *	@param devid long int Device ID [memory]
 *	@param state string State [memory] optional default false (true= Not '0')
 *	@param pgsz long int Paging Size [memory] optional default false
 *	@param pgno long int Paging Index [memory] optional default 1
 *	@param total long int Paging Total [memory] optional default false
 *
 *	@return result array Device key information [memory]
 *	@return total long int Paging total [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class DeviceListWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('devid'),
			'optional' => array('state' => false, 'pgsz' => false, 'pgno' => 0, 'total' => false)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['msg'] = 'Device keys returned successfully';
		
		$last = '';
		$args = array('devid');
		$escparam = array();
		
		if($memory['state'] === true){
			$last = " and `state`<>'0' ";
		}
		else if($memory['state']){
			$last = " and `state`='\${state}' ";
			array_push($escparam, 'state');
			array_push($args, 'state');
		}	
		
		$service = array(
			'service' => 'transpera.relation.select.workflow',
			'args' => $args,
			'conn' => 'cbconn',
			'relation' => '`devices`',
			'sqlprj' => '`keyid`',
			'sqlcnd' => "where `devid`=\${devid} $last",
			'escparam' => $escparam,
			'errormsg' => 'No Devices'
		);
		
		return Snowblozm::run($service, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('result', 'total');
	}
	
}

?>