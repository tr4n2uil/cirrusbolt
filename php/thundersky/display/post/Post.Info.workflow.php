<?php 
require_once(SBSERVICE);

/**
 *	@class PostInfoWorkflow
 *	@desc Returns post information by ID
 *
 *	@param postid long int Post ID [memory]
 *	@param keyid long int Usage Key ID [memory] optional default false
 *	@param boardid long int Board ID [memory] optional default 0
 *	@param bname string Board name [memory] optional default ''
 *
 *	@return post array Post information [memory]
 *	@return bname string Board name [memory]
 *	@return boardid long int Board ID [memory]
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
			'required' => array('postid'),
			'optional' => array('keyid' => false, 'bname' => '', 'boardid' => 0)
		); 
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$service = array(
			'service' => 'transpera.entity.info.workflow',
			'input' => array('id' => 'postid', 'parent' => 'boardid'),
			'conn' => 'cbdspcn',
			'relation' => '`posts`',
			'sqlcnd' => "where `postid`=\${id}",
			'errormsg' => 'Invalid Post ID',
			'successmsg' => 'Post information given successfully',
			'output' => array('entity' => 'post')
		);
		
		return Snowblozm::run($service, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('post', 'bname', 'boardid', 'admin');
	}
	
}

?>