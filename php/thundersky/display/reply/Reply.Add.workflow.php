<?php 
require_once(SBSERVICE);

/**
 *	@class ReplyAddWorkflow
 *	@desc Adds new reply
 *
 *	@param reply string Reply [memory]
 *	@param keyid long int Usage Key ID [memory]
 *	@param user string Key User [memory]
 *	@param postid long int Post ID [memory] optional default 0
 *	@param pname string Post Name [memory] optional default ''
 *	@param level integer Web level [memory] optional default false (inherit post admin access)
 *	@param owner long int Owner ID [memory] optional default keyid
 *
 *	@return replyid long int Reply ID [memory]
 *	@return postid long int Post ID [memory]
 *	@return pname string Post Name [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class ReplyAddWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('keyid', 'user', 'reply'),
			'optional' => array('postid' => 0, 'pname' => '', 'level' => false, 'owner' => false)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['verb'] = 'replied';
		$memory['join'] = 'to';
		$memory['public'] = 1;
		
		$service = array(
			'service' => 'transpera.entity.add.workflow',
			'args' => array('reply'),
			'input' => array('parent' => 'postid', 'cname' => 'reply', 'pname' => 'pname'),
			'conn' => 'cbdconn',
			'relation' => '`replies`',
			'sqlcnd' => "(`replyid`, `owner`, `reply`) values (\${id}, \${owner}, '\${user}', '\${reply}')",
			'escparam' => array('reply'),
			'successmsg' => 'Reply added successfully',
			'output' => array('id' => 'replyid')
		);
		
		return Snowblozm::run($service, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('replyid', 'postid', 'pname');
	}
	
}

?>