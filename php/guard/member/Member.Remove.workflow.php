<?php 
require_once(SBSERVICE);

/**
 *	@class MemberRemoveWorkflow
 *	@desc Removes member key from chain
 *
 *	@param keyid long int Key ID [memory]
 *	@param chainid long int Chain ID [memory]
 *	@param type string Type name [memory] optional default 'general'
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class MemberRemoveWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('keyid', 'chainid'),
			'optional' => array('type' => 'general')
		);
	}

	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['msg'] = 'Member removed successfully';
		
		$service = array(
			'service' => 'transpera.relation.delete.workflow',
			'args' => array('keyid', 'chainid', 'type'),
			'conn' => 'cbconn',
			'relation' => '`members`',
			'sqlcnd' => "where `type`='\${type}' and `keyid`=\${keyid} and `chainid`=\${chainid}",
			'escparam' => array('type'),
			'errormsg' => 'Invalid Member ID'
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