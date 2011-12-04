<?php 
require_once(SBSERVICE);

/**
 *	@class ChainAddWorkflow
 *	@desc Adds member key to Chain
 *
 *	@param keyid long int Key ID [memory]
 *	@param chainid long int Chain ID [memory]
 *
 *	@return return id long int Chain member key ID [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class ChainAddWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('keyid', 'chainid')
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['msg'] = 'Chain member added successfully';
		
		$service = array(
			'service' => 'transpera.relation.insert.workflow',
			'args' => array('chainid', 'keyid'),
			'conn' => 'cbconn',
			'relation' => '`members`',
			'sqlcnd' => "(`chainid`, `keyid`) values (\${chainid}, \${keyid})"
		);
		
		return Snowblozm::run($service, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('id');
	}
	
}

?>