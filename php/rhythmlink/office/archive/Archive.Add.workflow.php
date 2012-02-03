<?php 
require_once(SBSERVICE);

/**
 *	@class ArchiveAddWorkflow
 *	@desc Adds new archive to person
 *
 *	@param name string Archive name [memory]
 *	@param role long int Role ID [memory]
 *	@param keyid long int Usage Key ID [memory]
 *	@param pnid long int Person ID [memory] optional default 0
 *
 *	@return arcid long int Archive ID [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class ArchiveAddWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('keyid', 'name', 'role'),
			'optional' => array('pnid' => 0)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$service = array(
			'service' => 'transpera.entity.add.workflow',
			'args' => array('name', 'role'),
			'input' => array('parent' => 'pnid'),
			'conn' => 'rtconn',
			'relation' => '`archives`',
			'sqlcnd' => "(`arcid`, `owner`, `name`, `role`) values (\${id}, \${owner}, '\${name}', \${role})",
			'escparam' => array('name'),
			'type' => 'archive',
			'successmsg' => 'Archive added successfully',
			'output' => array('id' => 'arcid')
		);
		
		return Snowblozm::run($service, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('arcid');
	}
	
}

?>