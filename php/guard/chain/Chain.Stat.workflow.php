<?php 
require_once(SBSERVICE);

/**
 *	@class ChainStatWorkflow
 *	@desc Returns chain statistics
 *
 *	@param chainid long int Chain ID [memory]
 *	@param rucache boolean Is cacheable [memory] optional default false
 *	@param ruexpiry int Cache expiry [memory] optional default 85
 *
 *	@return stat array Statistics information [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class ChainStatWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('chainid'),
			'optional' => array('rucache' => false, 'ruexpiry' => 85)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['msg'] = 'Chain information returned successfully';
		
		$service = array(
			'service' => 'transpera.relation.unique.workflow',
			'args' => array('chainid'),
			'conn' => 'cbconn',
			'relation' => '`chains`',
			'sqlprj' => '`links`, `reads`, `writes`, `ctime`, `rtime`, `wtime`',
			'sqlcnd' => "where `chainid`=\${chainid}",
			'errormsg' => 'Invalid Chain ID',
			'output' => array('result' => 'stat')
		);
		
		return Snowblozm::run($service, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('stat');
	}
	
}

?>