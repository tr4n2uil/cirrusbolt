<?php 
require_once(SBSERVICE);

/**
 *	@class PersonRemoveWorkflow
 *	@desc Removes person by ID
 *
 *	@param pnid long int Person ID [memory]
 *	@param peopleid long int People ID [memory] optional default 5
 *	@param keyid long int Usage Key ID [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class PersonRemoveWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('keyid', 'pnid'),
			'optional' => array('peopleid' => 5)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['msg'] = 'Person removed successfully';
		
		$workflow = array(
		array(
			'service' => 'people.person.info.workflow'
		),
		array(
			'service' => 'transpera.entity.remove.workflow',
			'input' => array('id' => 'pnid', 'parent' => 'peopleid'),
			'conn' => 'cbpconn',
			'relation' => '`persons`',
			'sqlcnd' => "where `pnid`=\${id}",
			'errormsg' => 'Invalid Person ID',
			'successmsg' => 'Person removed successfully'
			'destruct' => array(
			array(
				'service' => 'storage.file.remove.workflow',
				'input' => array('fileid' => 'thumbnail'),
				'dirid' => PERSON_THUMB
			))
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