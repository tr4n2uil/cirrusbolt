<?php 
require_once(SBSERVICE);

/**
 *	@class PersonFindWorkflow
 *	@desc Returns person information by user
 *
 *	@param keyid long int Usage Key ID [memory]
 *	@param user string Person User [memory]
 *	@param peopleid long int People ID [memory] optional default 0
 *
 *	@return person array Person information [memory]
 *	@return contact array Person contact information [memory]
 *	@return personal array Person personal information [memory]
 *	@return pnid long int Person ID [memory]
 *	@return name string Person name [memory]
 *	@return title string Person title [memory]
 *	@return thumbnail long int Person thumbnail ID [memory]
 *	@return dirid long int Thumbnail Directory ID [memory]
 *	@return username string Person username [memory]
 *	@return peopleid long int People ID [memory]
 *	@return admin integer Is admin [memory]
 *	@return chain array Chain data [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class PersonFindWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('user', 'keyid'),
			'optional' => array('peopleid' => 0)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['msg'] = 'Person information given successfully';
		$memory['dirid'] = PERSON_THUMB;
		
		$workflow = array(
		array(
			'service' => 'transpera.entity.find.workflow',
			'input' => array('parent' => 'peopleid'),
			'args' => array('user'),
			'idkey' => 'pnid',
			'conn' => 'rlconn',
			'relation' => '`persons`',
			'sqlprj' => '`pnid`, `username`, `name`, `thumbnail`, `title`, `role`',
			'sqlcnd' => "where `username`='\${user}'",
			'escparam' => array('user'),
			'errormsg' => 'Invalid Username',
			'successmsg' => 'Person information given successfully',
			'output' => array('entity' => 'person', 'id' => 'pnid')
		),
		array(
			'service' => 'cbcore.data.select.service',
			'args' => array('person'),
			'params' => array('person.pnid' => 'pnid',/* 'person.name' => 'name', 'person.title' => 'title', 'person.thumbnail' => 'thumbnail', 'person.username' => 'username'*/)
		),
		array(
			'service' => 'people.person.profile.service'
		));
		
		return Snowblozm::execute($workflow, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('person', 'contact', 'personal', 'pnid', /*'name', 'title', 'thumbnail', 'username',*/ 'dirid', 'peopleid', 'admin', 'chain');
	}
	
}

?>