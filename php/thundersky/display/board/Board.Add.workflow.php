<?php 
require_once(SBSERVICE);

/**
 *	@class BoardAddWorkflow
 *	@desc Adds new board
 *
 *	@param bname string Board name [memory]
 *	@param desc string Board description [memory] optional default ''
 *	@param keyid long int Usage Key ID [memory]
 *	@param user string Key User [memory]
 *	@param forumid long int Forum ID [memory] optional default 0
 *	@param fname string Forum Name [memory] optional default ''
 *	@param level integer Web level [memory] optional default false (inherit forum admin access)
 *	@param owner long int Owner ID [memory] optional default keyid
 *
 *	@return boardid long int Board ID [memory]
 *	@return forumid long int Forum ID [memory]
 *	@return fname string Forum Name [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class BoardAddWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('keyid', 'user', 'bname'),
			'optional' => array('forumid' => 0, 'fname' => '', 'desc' => '', 'level' => false, 'owner' => false)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['verb'] = 'added';
		$memory['join'] = 'to';
		$memory['public'] = 1;
		
		$service = array(
			'service' => 'transpera.entity.add.workflow',
			'args' => array('bname', 'desc'),
			'input' => array('parent' => 'forumid', 'cname' => 'bname', 'pname' => 'fname'),
			'conn' => 'cbdconn',
			'relation' => '`boards`',
			'type' => 'board',
			'sqlcnd' => "(`boardid`, `owner`, `bname`, `desc`) values (\${id}, \${owner}, '\${bname}', '\${desc}')",
			'escparam' => array('bname', 'desc'),
			'successmsg' => 'Board added successfully',
			'output' => array('id' => 'boardid')
		);
		
		return Snowblozm::run($service, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('boardid', 'forumid', 'fname');
	}
	
}

?>