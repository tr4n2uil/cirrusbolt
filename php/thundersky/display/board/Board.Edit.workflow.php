<?php 
require_once(SBSERVICE);

/**
 *	@class BoardEditWorkflow
 *	@desc Edits board using ID
 *
 *	@param boardid long int Board ID [memory]
 *	@param title string Board title [memory]
 *	@param bname string Board name [memory]
 *	@param keyid long int Usage Key ID [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class BoardEditWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('keyid', 'boardid', 'bname')
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){		
		$memory['public'] = 1;
		
		$service = array(
			'service' => 'transpera.entity.edit.workflow',
			'args' => array('bname'),
			'input' => array('id' => 'boardid', 'cname' => 'bname'),
			'conn' => 'cbdconn',
			'relation' => '`replies`',
			'sqlcnd' => "set `bname`='\${bname}', `time`=now() where `boardid`=\${id}",
			'escparam' => array('bname'),
			'successmsg' => 'Board edited successfully'
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