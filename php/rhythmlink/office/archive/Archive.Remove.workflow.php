<?php 
require_once(SBSERVICE);

/**
 *	@class ArchiveRemoveWorkflow
 *	@desc Removes archive by ID
 *
 *	@param arcid long int Archive ID [memory]
 *	@param pnid long int Person ID [memory] optional default 0
 *	@param keyid long int Usage Key ID [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class ArchiveRemoveWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('keyid', 'arcid'),
			'optional' => array('pnid' => 0)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$service = array(
			'service' => 'transpera.entity.remove.workflow',
			'input' => array('id' => 'arcid', 'parent' => 'pnid'),
			'conn' => 'rtconn',
			'relation' => '`archives`',
			'sqlcnd' => "where `arcid`=\${id}",
			'errormsg' => 'Invalid Archive ID',
			'successmsg' => 'Archive removed successfully'
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