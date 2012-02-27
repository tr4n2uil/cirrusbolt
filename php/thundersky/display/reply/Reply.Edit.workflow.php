<?php 
require_once(SBSERVICE);

/**
 *	@class ReplyEditWorkflow
 *	@desc Edits reply using ID
 *
 *	@param replyid long int Reply ID [memory]
 *	@param title string Reply title [memory]
 *	@param reply string Reply [memory]
 *	@param keyid long int Usage Key ID [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class ReplyEditWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('keyid', 'replyid', 'reply')
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){		
		$memory['public'] = 1;
		
		$service = array(
			'service' => 'transpera.entity.edit.workflow',
			'args' => array('reply'),
			'input' => array('id' => 'replyid', 'cname' => 'reply'),
			'conn' => 'cbdconn',
			'relation' => '`replies`',
			'sqlcnd' => "set `reply`='\${reply}', `time`=now() where `replyid`=\${id}",
			'escparam' => array('reply'),
			'successmsg' => 'Reply edited successfully'
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