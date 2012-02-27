<?php 
require_once(SBSERVICE);

/**
 *	@class ReplyRemoveWorkflow
 *	@desc Removes reply by ID
 *
 *	@param replyid long int Reply ID [memory]
 *	@param keyid long int Usage Key ID [memory]
 *	@param postid long int Post ID [memory] optional default 0
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class ReplyRemoveWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('keyid', 'replyid'),
			'optional' => array('postid' => 0)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$service = array(
			'service' => 'transpera.entity.remove.workflow',
			'input' => array('id' => 'replyid', 'parent' => 'postid'),
			'conn' => 'cbdconn',
			'relation' => '`replies`',
			'sqlcnd' => "where `replyid`=\${id}",
			'errormsg' => 'Invalid Reply ID',
			'successmsg' => 'Reply removed successfully'
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