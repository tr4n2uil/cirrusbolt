<?php 
require_once(SBSERVICE);

/**
 *	@class ReplyInfoWorkflow
 *	@desc Returns reply information by ID
 *
 *	@param replyid long int Reply ID [memory]
 *	@param keyid long int Usage Key ID [memory] optional default false
 *	@param postid long int Post ID [memory] optional default 0
 *	@param pname string Post name [memory] optional default ''
 *
 *	@return reply array Reply information [memory]
 *	@return pname string Post name [memory]
 *	@return postid long int Post ID [memory]
 *	@return admin integer Is admin [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class ReplyInfoWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('replyid'),
			'optional' => array('keyid' => false, 'pname' => false, 'name' => '', 'postid' => false, 'id' => 0)
		); 
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['replyid'] = $memory['replyid'] ? $memory['replyid'] : $memory['id'];
		
		$service = array(
			'service' => 'transpera.entity.info.workflow',
			'input' => array('id' => 'replyid', 'parent' => 'postid', 'cname' => 'name', 'pname' => 'pname'),
			'conn' => 'cbdconn',
			'relation' => '`replies`',
			'sqlcnd' => "where `replyid`=\${id}",
			'errormsg' => 'Invalid Reply ID',
			'successmsg' => 'Reply information given successfully',
			'output' => array('entity' => 'reply')
		);
		
		return Snowblozm::run($service, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('reply', 'pname', 'postid', 'admin');
	}
	
}

?>