<?php 
require_once(SBSERVICE);

/**
 *	@class ArchiveEditWorkflow
 *	@desc Edits archive using ID
 *
 *	@param arcid long int Archive ID [memory]
 *	@param name string Archive name [memory]
 *	@param role long int Role ID [memory]
 *	@param keyid long int Usage Key ID [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class ArchiveEditWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('keyid', 'arcid', 'name', 'role')
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$service = array(
			'service' => 'transpera.entity.edit.workflow',
			'args' => array('name', 'role'),
			'input' => array('id' => 'arcid'),
			'conn' => 'rtconn',
			'relation' => '`archives`',
			'sqlcnd' => "set `name`='\${name}', `role`=\${role} where `arcid`=\${id}",
			'escparam' => array('name'),
			'successmsg' => 'Archive edited successfully'
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