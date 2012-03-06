<?php 
require_once(SBSERVICE);

/**
 *	@class CommentReplyWorkflow
 *	@desc Replies comment using ID
 *
 *	@param cmtid long int Comment ID [memory]
 *	@param reply string Comment reply [memory]
 *	@param keyid long int Usage Key ID [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class CommentReplyWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('keyid', 'cmtid', 'reply')
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['verb'] = 'replied';
		$memory['join'] = 'on';
		$memory['public'] = 1;
		
		$service = array(
			'service' => 'transpera.entity.edit.workflow',
			'args' => array('reply'),
			'input' => array('id' => 'cmtid', 'cname' => 'reply'),
			'init' => false,
			'conn' => 'cbdconn',
			'relation' => '`comments`',
			'type' => 'comment',
			'sqlcnd' => "set `reply`='\${reply}' where `cmtid`=\${id}",
			'escparam' => array('reply'),
			'check' => false,
			'successmsg' => 'Comment replied successfully'
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