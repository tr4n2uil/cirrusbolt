<?php 
require_once(SBSERVICE);

/**
 *	@class PersonAddWorkflow
 *	@desc Adds new person
 *
 *	@param name string Person name [memory]
 *	@param username string Person username [memory]
 *	@param password string Password [memory] 
 *	@param recaptcha_challenge_field string Challenge [memory]
 *	@param recaptcha_response_field string Response [memory] 
 *	@param email string Email [memory] optional default false
 *	@param phone string Phone [memory] optional default false
 *	@param device string Device to verify [memory] optional default 'mail' ('mail', 'sms')
 *	@param location long int Location [memory] optional default 0
 *	@param keyid long int Usage Key [memory] optional default false
 *	@param peopleid long int People ID [memory] optional default 0
 *	@param level integer Web level [memory] optional default 1 (people admin access allowed)
 *
 *	@return pnid long int Person ID [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class PersonAddWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('name', 'username', 'password','recaptcha_challenge_field', 'recaptcha_response_field'),
			'optional' => array('keyid' => false, 'email' => false, 'phone' => false, 'peopleid' => 5, 'level' => 1, 'location' => 0, 'device' => 'email')
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['msg'] = 'Person added successfully';
		$memory['peopleid'] = 5;
		$memory['level'] = 1;
		
		$workflow = array(
		array(
			'service' => 'invoke.human.recaptcha.service'
		),
		array(
			'service' => 'people.person.available.workflow'
		),
		array(
			'service' => 'transpera.reference.create.workflow',
			'input' => array('keyvalue' => 'password', 'parent' => 'peopleid', 'user' => 'username'),
			'output' => array('id' => 'pnid'),
			'root' => '/'.$memory['username'],
			'type' => 'person',
			'authorize' => 'add:remove:edit:list:con:per:rel:sub:act:eme'
		),
		array(
			'service' => 'storage.file.add.workflow',
			'ext' => 'png',
			'mime' => 'image/png',
			'dirid' => PERSON_THUMB,
			'input' => array('name' => 'username', 'user' => 'username'),	//@possible 'level' => 2,
			'output' => array('fileid' => 'thumbnail')
		),
		array(
			'service' => 'people.role.add.workflow',
			'name' => 'Global',
			'desc' => 'Default Role',
			'input' => array('user' => 'username'),
			'output' => array('rlid' => 'role')
		),
		array(
			'service' => 'transpera.relation.insert.workflow',
			'args' => array('pnid', 'name', 'username', 'owner', 'thumbnail', 'email', 'phone', 'location', 'role', 'device'),
			'conn' => 'rlconn',
			'relation' => '`persons`',
			'sqlcnd' => "(`pnid`, `name`, `username`, `owner`, `thumbnail`, `email`, `phone`, `location`, `role`, `device`) values (\${pnid}, '\${name}', '\${username}', \${owner}, \${thumbnail}, '\${email}', '\${phone}', \${location}, \${role}, '\${device}')",
			'escparam' => array('name', 'username',  'email', 'phone', 'device')
		));
		
		return Snowblozm::execute($workflow, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('pnid');
	}
	
}

?>