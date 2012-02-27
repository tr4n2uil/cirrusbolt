<?php 
require_once(SBSERVICE);

/**
 *	@class BoardListWorkflow
 *	@desc Returns all replies information in forum
 *
 *	@param keyid long int Usage Key ID [memory]
 *	@param forumid/id long int Forum ID [memory] optional default 0
 *	@param fname/name string Forum name [memory] optional default ''
 *
 *	@param pgsz long int Paging Size [memory] optional default false
 *	@param pgno long int Paging Index [memory] optional default 1
 *	@param total long int Paging Total [memory] optional default false
 *
 *	@return replies array Replies information [memory]
 *	@return forumid long int Forum ID [memory]
 *	@return fname string Forum name [memory]
 *	@return admin integer Is admin [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class BoardListWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('keyid'),
			'optional' => array('forumid' => false, 'id' => 0, 'fname' => false, 'name' => '', 'pgsz' => false, 'pgno' => 0, 'total' => false)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['forumid'] = $memory['forumid'] ? $memory['forumid'] : $memory['id'];
		$memory['fname'] = $memory['fname'] ? $memory['fname'] : $memory['name'];
		
		$service = array(
			'service' => 'transpera.entity.list.workflow',
			'input' => array('id' => 'forumid', 'pname' => 'fname'),
			'conn' => 'cbdconn',
			'relation' => '`replies`',
			'sqlprj' => '`boardid`, `bname`',
			'sqlcnd' => "where `boardid` in \${list} order by `boardid` desc",
			'successmsg' => 'Replies information given successfully',
			'lsttrack' => true,
			'output' => array('entities' => 'replies'),
		);
		
		return Snowblozm::run($service, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('replies', 'forumid', 'fname', 'admin');
	}
	
}

?>