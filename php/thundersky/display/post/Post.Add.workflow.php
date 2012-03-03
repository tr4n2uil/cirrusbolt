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
 *	@param bname string Board Name [memory] optional default ''
 *	@param level integer Web level [memory] optional default false (inherit board admin access)
 *	@param owner long int Owner ID [memory] optional default keyid
 *
 *	@return postid long int Post ID [memory]
 *	@return boardid long int Board ID [memory]
 *	@return bname string Board Name [memory]
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
			'optional' => array('boardid' => 0, 'bname' => '', 'level' => false, 'owner' => false)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['verb'] = 'posted';
		$memory['join'] = 'to';
		$memory['public'] = 1;
		
		$service = array(
			'service' => 'transpera.entity.add.workflow',
			'args' => array('title', 'post'),
			'input' => array('parent' => 'boardid', 'cname' => 'title', 'pname' => 'bname'),
			'conn' => 'cbdconn',
			'relation' => '`posts`',
			'type' => 'post',
			'sqlcnd' => "(`postid`, `owner`, `title`, `post`) values (\${id}, \${owner}, '\${title}', '\${post}')",
			'escparam' => array('title', 'post'),
			'successmsg' => 'Post added successfully',
			'output' => array('id' => 'postid')
		);
		
		return Snowblozm::run($service, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('postid', 'boardid', 'bname');
	}
	
}

?>