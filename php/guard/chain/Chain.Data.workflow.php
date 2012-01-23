<?php 
require_once(SBSERVICE);

/**
 *	@class ChainDataWorkflow
 *	@desc Returns chain data information
 *
 *	@param chainid long int Chain ID [memory]
 *
 *	@return chain array Chain data information [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class ChainDataWorkflow implements Service {
	
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
		$memory['msg'] = 'Chain data information returned successfully';
		
		$service = array(
			'service' => 'transpera.relation.unique.workflow',
			'args' => array('chainid'),
			'conn' => 'cbconn',
			'relation' => '`chains`',
			'sqlprj' => '`masterkey`, `level`, `authorize`, `state`',
			'sqlcnd' => "where `chainid`=\${chainid}",
			'errormsg' => 'Invalid Chain ID',
			'output' => array('result' => 'chain')
		);
		
		return Snowblozm::run($service, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('chain');
	}
	
}

?>