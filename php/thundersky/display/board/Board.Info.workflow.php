<?php 
require_once(SBSERVICE);

/**
 *	@class BoardInfoWorkflow
 *	@desc Returns board information by ID
 *
 *	@param boardid long int Board ID [memory]
 *	@param keyid long int Usage Key ID [memory] optional default false
 *	@param forumid long int Forum ID [memory] optional default 0
 *	@param fname string Forum name [memory] optional default ''
 *
 *	@return board array Board information [memory]
 *	@return fname string Forum name [memory]
 *	@return forumid long int Forum ID [memory]
 *	@return admin integer Is admin [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class BoardInfoWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('boardid'),
			'optional' => array('keyid' => false, 'fname' => false, 'name' => '', 'forumid' => false, 'id' => 0)
		); 
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['boardid'] = $memory['boardid'] ? $memory['boardid'] : $memory['id'];
		
		$service = array(
			'service' => 'transpera.entity.info.workflow',
			'input' => array('id' => 'boardid', 'parent' => 'forumid', 'cname' => 'name', 'pname' => 'fname'),
			'conn' => 'cbdconn',
			'relation' => '`replies`',
			'sqlcnd' => "where `boardid`=\${id}",
			'errormsg' => 'Invalid Board ID',
			'successmsg' => 'Board information given successfully',
			'output' => array('entity' => 'board')
		);
		
		return Snowblozm::run($service, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('board', 'fname', 'forumid', 'admin');
	}
	
}

?>