<?php 
require_once(SBSERVICE);

/**
 *	@class SpaceRemoveWorkflow
 *	@desc Removes space by ID
 *
 *	@param spaceid long int Space ID [memory]
 *	@param keyid long int Usage Key ID [memory]
 *	@param cntrid long int Container ID [memory] optional default 0
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class SpaceRemoveWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('keyid', 'spaceid'),
			'optional' => array('cntrid' => 0)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['msg'] = 'Space removed successfully';
		
		$workflow = array(
		array(
			'service' => 'store.space.info.workflow'
		),
		array(
			'service' => 'transpera.reference.remove.workflow',
			'input' => array('parent' => 'cntrid', 'id' => 'spaceid')
		),
		array(
			'service' => 'transpera.relation.delete.workflow',
			'args' => array('spaceid'),
			'conn' => 'cbconn',
			'relation' => '`spaces`',
			'sqlcnd' => "where `spaceid`=\${spaceid}",
			'errormsg' => 'Invalid Space ID'
		),
		array(
			'service' => 'cbcore.file.unlink.service',
			'input' => array('filepath' => 'sppath'),
			'filename' => 'archive.zip'
		),
		array(
			'service' => 'cbcore.file.rmdir.service',
			'input' => array('directory' => 'sppath')
		));
		
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