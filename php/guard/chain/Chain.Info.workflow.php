<?php 
require_once(SBSERVICE);

/**
 *	@class ChainInfoWorkflow
 *	@desc Returns chain information
 *
 *	@param chainid long int Chain ID [memory]
 *
 *	@return masterkey long int Master key ID [memory]
 *	@return level integer Level [memory]
 *	@return authorize string Authorization Control [memory]
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
			'required' => array('chainid')
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['msg'] = 'Chain information returned successfully';
		
		$workflow = array(
		array(
			'service' => 'ad.relation.unique.workflow',
			'args' => array('chainid'),
			'conn' => 'adconn',
			'relation' => '`chains`',
			'sqlprj' => '`masterkey`, `level`, `authorize`',
			'sqlcnd' => "where `chainid`=\${chainid}",
			'errormsg' => 'Invalid Chain ID'
		),
		array(
			'service' => 'adcore.data.select.service',
			'args' => array('result'),
			'params' => array('result.0.masterkey' => 'masterkey', 'result.0.level' => 'level', 'result.0.authorize' => 'authorize')
		));
		
		return Snowblozm::execute($workflow, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('masterkey', 'level', 'authorize');
	}
	
}

?>