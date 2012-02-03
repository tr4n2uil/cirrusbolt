<?php 
require_once(SBSERVICE);

/**
 *	@class EntryRemoveWorkflow
 *	@desc Removes entry from archive
 *
 *	@param tcid long int Topic ID [memory]
 *	@param arcid long int Archive ID [memory] optional default 0
 *	@param keyid long int Usage Key ID [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class EntryRemoveWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('keyid', 'tcid'),
			'optional' => array('arcid' => 0)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['msg'] = 'Entry removed successfully';
		
		$workflow = array(
		array(
			'service' => 'transpera.reference.authorize.workflow',
			'input' => array('id' => 'arcid'),
			'action' => 'remove'
		),
		array(
			'service' => 'transpera.relation.delete.workflow',
			'args' => array('arcid', 'tcid'),
			'conn' => 'rtconn',
			'relation' => '`entries`',
			'sqlcnd' => "where `arcid`=\${arcid} and `tcid`=\${tcid}",
			'errormsg' => 'Invalid Entry'
		);
		
		return Snowblozm::execute($workflow, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array();
	}
	
}

?>