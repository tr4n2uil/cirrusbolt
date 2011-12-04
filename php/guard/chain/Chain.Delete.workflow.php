<?php 
require_once(SBSERVICE);

/**
 *	@class ChainDeleteWorkflow
 *	@desc Removes chain using ID
 *
 *	@param chainid long int Chain ID [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class ChainDeleteWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('chainid')
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['msg'] = 'Chain deleted successfully';
		
		$service = array(
			'service' => 'ad.relation.delete.workflow',
			'args' => array('chainid'),
			'conn' => 'adconn',
			'relation' => '`chains`',
			'sqlcnd' => "where `chainid`=\${chainid}",
			'errormsg' => 'Invalid Chain ID'
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