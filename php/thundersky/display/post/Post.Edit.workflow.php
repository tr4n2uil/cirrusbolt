<?php 
require_once(SBSERVICE);

/**
 *	@class PostEditWorkflow
 *	@desc Edits post using ID
 *
 *	@param postid long int Post ID [memory]
 *	@param title string Post title [memory]
 *	@param post string Post [memory]
 *	@param keyid long int Usage Key ID [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class PostEditWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('keyid', 'postid', 'title', 'post')
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){		
		$memory['public'] = 1;
		
		$service = array(
			'service' => 'transpera.entity.edit.workflow',
			'args' => array('title', 'post'),
			'input' => array('id' => 'postid', 'cname' => 'title'),
			'conn' => 'cbdconn',
			'relation' => '`posts`',
			'type' => 'post',
			'sqlcnd' => "set `title`='\${title}', `post`='\${post}' where `postid`=\${id}",
			'escparam' => array('title', 'post'),
			'successmsg' => 'Post edited successfully'
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