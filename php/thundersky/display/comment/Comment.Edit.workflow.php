<?php 
require_once(SBSERVICE);

/**
 *	@class CommentEditWorkflow
 *	@desc Edits comment using ID
 *
 *	@param cmtid long int Comment ID [memory]
 *	@param comment string Comment [memory]
 *	@param keyid long int Usage Key ID [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class CommentEditWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('keyid', 'cmtid', 'comment')
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){		
		$memory['public'] = 1;
		
		$service = array(
			'service' => 'transpera.entity.edit.workflow',
			'args' => array('comment'),
			'input' => array('id' => 'cmtid', 'cname' => 'comment'),
			'conn' => 'cbdconn',
			'relation' => '`comments`',
			'type' => 'comment',
			'sqlcnd' => "set `comment`='\${comment}' where `cmtid`=\${id}",
			'escparam' => array('comment'),
			'successmsg' => 'Comment edited successfully'
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