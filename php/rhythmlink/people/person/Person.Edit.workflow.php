<?php 
require_once(SBSERVICE);

/**
 *	@class PersonEditWorkflow
 *	@desc Edits person using ID
 *
 *	@param pnid long int Person ID [memory]
 *	@param name string Person name [memory]
 *	@param title string Title [memory]
 *	@param dateofbirth string Date of birth [memory] (Format YYYY-MM-DD)
 *	@param gender string Gender [memory]  (M=Male F=Female N=None)
 *	@param address string Address [memory] 
 *	@param location long int Location [memory] optional default 0
 *	@param keyid long int Usage Key ID [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class PersonEditWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('keyid', 'pnid', 'name', 'title', 'dateofbirth', 'gender', 'address'),
			'optional' => array('location' => 0)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$dob = $memory['dateofbirth'] ? "'\${dateofbirth}'" : 'null';
	
		$service = array(
			'service' => 'transpera.entity.edit.workflow',
			'args' => array('name', 'title', 'dateofbirth', 'gender', 'address', 'location'),
			'input' => array('id' => 'pnid'),
			'conn' => 'rlconn',
			'relation' => '`persons`',
			'sqlcnd' => "set `name`='\${name}', `title`='\${title}', `dateofbirth`=$dob, `gender`='\${gender}', `address`='\${address}', `location`=\${location} where `pnid`=\${id}",
			'escparam' => array('name', 'title', 'dateofbirth', 'gender', 'address'),
			'successmsg' => 'Person edited successfully',
			'errormsg' => 'No Change / Invalid Person ID'
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