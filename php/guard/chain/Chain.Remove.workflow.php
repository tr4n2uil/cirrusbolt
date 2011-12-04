<?php 
require_once(SBSERVICE);

/**
 *	@class ChainRemoveWorkflow
 *	@desc Removes member key from chain
 *
 *	@param keyid long int Key ID [memory]
 *	@param chainid long int Chain ID [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class ChainRemoveWorkflow implements Service {
	
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
		$memory['msg'] = 'Chain member removed successfully';
		
		$service = array(
			'service' => 'ad.relation.delete.workflow',
			'args' => array('keyid', 'chainid'),
			'conn' => 'adconn',
			'relation' => '`members`',
			'sqlcnd' => "where `keyid`=\${keyid} and `chainid`=\${chainid}",
			'errormsg' => 'Invalid Parent Chain ID'
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