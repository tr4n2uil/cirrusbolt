<?php 
require_once(SBSERVICE);

/**
 *	@class PostAddWorkflow
 *	@desc Adds new post
 *
 *	@param title string Post title [memory]
 *	@param post string Post [memory]
 *	@param keyid long int Usage Key ID [memory]
 *	@param user string Key User [memory]
 *	@param boardid long int Board ID [memory] optional default 0
 *	@param level integer Web level [memory] optional default false (inherit board admin access)
 *	@param owner long int Owner ID [memory] optional default keyid
 *
 *	@return postid long int Post ID [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class PostAddWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('keyid', 'user', 'title', 'post'),
			'optional' => array('boardid' => 0, 'level' => false, 'owner' => false)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$service = array(
			'service' => 'transpera.entity.add.workflow',
			'args' => array('post'),
			'input' => array('parent' => 'boardid'),
			'conn' => 'cbdconn',
			'relation' => '`posts`',
			'sqlcnd' => "(`postid`, `owner`, `title`, `post`) values (\${id}, \${owner}, '\${user}', '\${post}')",
			'escparam' => array('post'),
			'successmsg' => 'Post added successfully',
			'output' => array('id' => 'postid')
		);
		
		return Snowblozm::run($service, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('postid');
	}
	
}

?>