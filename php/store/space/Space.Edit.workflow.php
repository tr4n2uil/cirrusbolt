<?php 
require_once(SBSERVICE);

/**
 *	@class SpaceEditWorkflow
 *	@desc Edits space of container
 *
 *	@param spaceid long int Space ID [memory]
 *	@param spname string Space name [memory]
 *	@param sppath string Space path [memory]
 *	@param keyid long int Usage Key ID [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class SpaceEditWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('keyid', 'spaceid', 'spname', 'sppath')
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$service = array(
			'service' => 'transpera.entity.edit.workflow',
			'input' => array('id' => 'spaceid'),
			'args' => array('spname', 'sppath'),
			'conn' => 'cbconn',
			'relation' => '`spaces`',
			'sqlcnd' => "set `spname`='\${spname}', `sppath`='\${sppath}' where `spaceid`=\${id}",
			'escparam' => array('spname', 'sppath'),
			'successmsg' => 'Space edited successfully'
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