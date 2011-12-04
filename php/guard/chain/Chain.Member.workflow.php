<?php 
require_once(SBSERVICE);

/**
 *	@class ChainMemberWorkflow
 *	@desc Returns member key IDs in chain
 *
 *	@param chainid long int Chain ID [memory]
 *
 *	@param result array Member key information [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class ChainMemberWorkflow implements Service {
	
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
		$memory['msg'] = 'Member keys returned successfully';
		
		$service = array(
			'service' => 'transpera.relation.select.workflow',
			'args' => array('chainid'),
			'conn' => 'cbconn',
			'relation' => '`members` m, `keys` k',
			'sqlprj' => 'k.`keyid`, k.`email`',
			'sqlcnd' => "where m.`chainid`=\${chainid} and m.`keyid`=k.`keyid`",
			'errormsg' => 'Invalid Chain ID / No Members'
		);
		
		return Snowblozm::run($service, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('result');
	}
	
}

?>