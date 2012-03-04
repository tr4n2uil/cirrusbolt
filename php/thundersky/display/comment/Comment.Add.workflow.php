<?php 
require_once(SBSERVICE);

/**
 *	@class CommentAddWorkflow
 *	@desc Adds new comment
 *
 *	@param comment string Comment [memory]
 *	@param keyid long int Usage Key ID [memory]
 *	@param user string Key User [memory]
 *	@param postid long int Post ID [memory] optional default 0
 *	@param pname string Post Name [memory] optional default ''
 *	@param level integer Web level [memory] optional default false (inherit post admin access)
 *	@param owner long int Owner ID [memory] optional default keyid
 *
 *	@return cmtid long int Comment ID [memory]
 *	@return postid long int Post ID [memory]
 *	@return pname string Post Name [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class CommentAddWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('keyid', 'user', 'comment'),
			'optional' => array('postid' => 0, 'pname' => '', 'level' => false, 'owner' => false)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['verb'] = 'commented';
		$memory['join'] = 'on';
		$memory['public'] = 1;
		
		$service = array(
			'service' => 'transpera.entity.add.workflow',
			'args' => array('comment'),
			'input' => array('parent' => 'postid', 'cname' => 'comment', 'pname' => 'pname'),
			'conn' => 'cbdconn',
			'relation' => '`comments`',
			'type' => 'comment',
			'sqlcnd' => "(`cmtid`, `owner`, `comment`) values (\${id}, \${owner}, '\${comment}')",
			'escparam' => array('comment'),
			'successmsg' => 'Comment added successfully',
			'output' => array('id' => 'cmtid')
		);
		
		return Snowblozm::run($service, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('cmtid', 'postid', 'pname');
	}
	
}

?>