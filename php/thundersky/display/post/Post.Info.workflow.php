<?php 
require_once(SBSERVICE);

/**
 *	@class PostInfoWorkflow
 *	@desc Returns post information by ID
 *
 *	@param postid long int Post ID [memory]
 *	@param keyid long int Usage Key ID [memory] optional default false
 *	@param name string Post title [memory] optional default ''
 *
 *	@return post array Post information [memory]
 *	@return name string Post title [memory]
 *	@return postid long int Post ID [memory]
 *	@return admin integer Is admin [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class PostInfoWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'optional' => array('keyid' => false, 'name' => '', 'id' => 0, 'postid' => false)
		); 
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['postid'] = $memory['postid'] ? $memory['postid'] : $memory['id'];
		
		$workflow = array(
		array(
			'service' => 'transpera.entity.info.workflow',
			'input' => array('id' => 'postid', 'parent' => 'boardid', 'cname' => 'name', 'pname' => 'bname'),
			'conn' => 'cbdconn',
			'relation' => '`posts`',
			'type' => 'post',
			'sqlcnd' => "where `postid`=\${id}",
			'errormsg' => 'Invalid Post ID',
			'successmsg' => 'Post information given successfully',
			'output' => array('entity' => 'post')
		),
		array(
			'service' => 'display.comment.list.workflow',
			'output' => array('admin' => 'cmntadmin')
		));
		
		return Snowblozm::execute($workflow, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('post', 'name', 'postid', 'chain', 'admin', 'comments', 'cmntadmin', 'total', 'pgsz', 'pgno');
	}
	
}

?>