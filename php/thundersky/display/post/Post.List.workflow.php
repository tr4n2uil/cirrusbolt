<?php 
require_once(SBSERVICE);

/**
 *	@class PostListWorkflow
 *	@desc Returns all posts information in board
 *
 *	@param keyid long int Usage Key ID [memory]
 *	@param boardid long int Board ID [memory] optional default 0
 *	@param bname string Board name [memory] optional default ''
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
			'optional' => array('boardid' => 0, 'bname' => '')
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$service = array(
			'service' => 'transpera.entity.list.workflow',
			'input' => array('id' => 'boardid'),
			'conn' => 'cbdspcn',
			'relation' => '`posts`',
			'sqlprj' => '`postid`, `title`, `time`, `author`',
			'sqlcnd' => "where `postid` in \${list} order by `time` desc",
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