<?php 
require_once(SBSERVICE);

/**
 *	@class EntryAddWorkflow
 *	@desc Adds new entry to archive
 *
 *	@param tcid long int Topic ID [memory]
 *	@param keyid long int Usage Key ID [memory]
 *	@param arcid long int Archive ID [memory] optional default 0
 *
 *	@return eyid long int Entry ID [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class EntryAddWorkflow implements Service {
	
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
		$memory['msg'] = 'Entry added successfully';
		
		$workflow = array(
		array(
			'service' => 'transpera.reference.authorize.workflow',
			'input' => array('id' => 'arcid'),
			'action' => 'add'
		),
		array(
			'service' => 'transpera.relation.insert.workflow',
			'args' => array('keyid', 'tcid', 'arcid'),
			'conn' => 'rtconn',
			'relation' => '`entries`',
			'sqlcnd' => "(`owner`, `arcid`, `tcid`) values (\${keyid}, \${arcid}, \${tcid})",
			'output' => array('id' => 'eyid')
		));
		
		return Snowblozm::execute($workflow, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('eyid');
	}
	
}

?>