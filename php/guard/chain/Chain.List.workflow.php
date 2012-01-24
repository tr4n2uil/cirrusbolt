<?php 
require_once(SBSERVICE);

/**
 *	@class ChainListWorkflow
 *	@desc Returns chain information
 *
 *	@param chainid set of long int Chain ID [memory]
 *	@param rstcache boolean Is cacheable [memory] optional default false
 *	@param rstexpiry int Cache expiry [memory] optional default 150
 *	@param rscache boolean Is cacheable [memory] optional default false
 *	@param rsexpiry int Cache expiry [memory] optional default 85
 *
 *	@return chains array Chains information [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class ChainListWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('chainid'),
			'optional' => array(
				'rscache' => false, 
				'rsexpiry' => 85,
				'rstcache' => false, 
				'rstexpiry' => 150
			)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['msg'] = 'Chain information returned successfully';
		
		$service = array(
			'service' => 'transpera.relation.select.workflow',
			'args' => array('chainid'),
			'conn' => 'cbconn',
			'relation' => '`chains`',
			'sqlprj' => '`chainid`, `authorize`, `state`',
			'sqlcnd' => "where `chainid` in \${chainid}",
			'escparam' => array('chainid'),
			'errormsg' => 'Invalid Chain ID',
			'check' => false,
			'output' => array('result' => 'chains'),
			'mapkey' => 'chainid',
			'mapname' => 'chain'
		);
		
		return Snowblozm::run($service, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('chains');
	}
	
}

?>