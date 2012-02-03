<?php 
require_once(SBSERVICE);

/**
 *	@class ArchiveInfoWorkflow
 *	@desc Returns archive information by ID
 *
 *	@param arcid string Archive ID [memory]
 *	@param keyid long int Usage Key ID [memory]
 *	@param pnid long int Person ID [memory] optional default 0
 *
 *	@return archive array Archive information [memory]
 *	@return pnid long int Person ID [memory]
 *	@return admin integer Is admin [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class ArchiveInfoWorkflow implements Service {
	
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
			'service' => 'transpera.entity.info.workflow',
			'input' => array('id' => 'arcid', 'parent' => 'pnid'),
			'conn' => 'rtconn',
			'relation' => '`archives`',
			'sqlcnd' => "where `arcid`='\${id}'",
			'errormsg' => 'Invalid Archive ID',
			'successmsg' => 'Archive information given successfully',
			'output' => array('entity' => 'archive')
		);
		
		return Snowblozm::run($service, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('archive', 'pnid', 'admin');
	}
	
}

?>
