<?php 
require_once(SBSERVICE);

/**
 *	@class ChainListWorkflow
 *	@desc Returns chain information
 *
 *	@param chainid set of long int Chain ID [memory]
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
			'required' => array('chainid')
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