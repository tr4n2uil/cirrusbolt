<?php 
require_once(SBSERVICE);

/**
 *	@class ChainInfoWorkflow
 *	@desc Returns chain information
 *
 *	@param chainid long int Chain ID [memory]
 *	@param rucache boolean Is cacheable [memory] optional default false
 *	@param ruexpiry int Cache expiry [memory] optional default 85
 *
 *	@return chain array Chain data information [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class ChainInfoWorkflow implements Service {
	
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
		
		$workflow = array(
		array(
			'service' => 'transpera.relation.unique.workflow',
			'args' => array('chainid'),
			'conn' => 'cbconn',
			'relation' => '`chains`',
			'sqlprj' => '`authorize`, `state`',
			'sqlcnd' => "where `chainid`=\${chainid}",
			'errormsg' => 'Invalid Chain ID'
		),
		array(
			'service' => 'cbcore.data.select.service',
			'args' => array('result'),
			'params' => array('result.0' => 'chain')
		));
		
		return Snowblozm::execute($workflow, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('chain');
	}
	
}

?>