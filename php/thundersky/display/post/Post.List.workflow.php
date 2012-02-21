<?php 
require_once(SBSERVICE);

/**
 *	@class PostListWorkflow
 *	@desc Returns all posts information in board
 *
 *	@param keyid long int Usage Key ID [memory]
 *	@param boardid/id long int Board ID [memory] optional default 0
 *	@param bname/name string Board name [memory] optional default ''
 *
 *	@param pgsz long int Paging Size [memory] optional default false
 *	@param pgno long int Paging Index [memory] optional default 1
 *	@param total long int Paging Total [memory] optional default false
 *
 *	@return posts array Posts information [memory]
 *	@return boardid long int Board ID [memory]
 *	@return bname string Board name [memory]
 *	@return admin integer Is admin [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class PostListWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('keyid'),
			'optional' => array('boardid' => false, 'id' => 0, 'bname' => false, 'name' => '', 'pgsz' => false, 'pgno' => 0, 'total' => false)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['boardid'] = $memory['boardid'] ? $memory['boardid'] : $memory['id'];
		$memory['bname'] = $memory['bname'] ? $memory['bname'] : $memory['name'];
		
		$service = array(
			'service' => 'transpera.entity.list.workflow',
			'input' => array('id' => 'boardid'),
			'conn' => 'cbdconn',
			'relation' => '`posts`',
			'sqlprj' => '`postid`, `title`, substring(`post`, 1, 50) as `post`',
			'sqlcnd' => "where `postid` in \${list} order by `postid` desc",
			'successmsg' => 'Posts information given successfully',
			'output' => array('entities' => 'posts'),
		);
		
		return Snowblozm::run($service, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('posts', 'boardid', 'bname', 'admin');
	}
	
}

?>