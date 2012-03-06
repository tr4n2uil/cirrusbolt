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
			'required' => array('keyid', 'cmtid', 'comment', 'postid', 'pname')
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){		
		$memory['public'] = 1;
		
		$workflow = array(
		array(
			'service' => 'transpera.entity.edit.workflow',
			'args' => array('comment'),
			'input' => array('id' => 'cmtid', 'cname' => 'comment'),
			'conn' => 'cbdconn',
			'relation' => '`comments`',
			'type' => 'comment',
			'sqlcnd' => "set `comment`='\${comment}' where `cmtid`=\${id}",
			'escparam' => array('comment'),
			'check' => false,
			'successmsg' => 'Comment edited successfully'
		),
		array(
			'service' => 'transpera.entity.info.workflow',
			'input' => array('id' => 'cmtid', 'parent' => 'postid', 'cname' => 'name', 'pname' => 'pname'),
			'conn' => 'cbdconn',
			'relation' => '`comments`',
			'sqlcnd' => "where `cmtid`=\${id}",
			'errormsg' => 'Invalid Comment ID',
			'type' => 'comment',
			'successmsg' => 'Comment information given successfully',
			'output' => array('entity' => 'comment'),
			'auth' => false,
			'track' => false,
			'init' => false
		));
		
		return Snowblozm::execute($workflow, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('postid', 'pname', 'comment', 'chain', 'admin');
	}
	
}

?>