<?php 
require_once(SBSERVICE);

/**
 *	@class PostInfoWorkflow
 *	@desc Returns post information by ID
 *
 *	@param postid/id long int Post ID [memory]
 *	@param keyid long int Usage Key ID [memory] optional default false
 *	@param pname/name string Post title [memory] optional default ''
 *
 *	@param pgsz long int Paging Size [memory] optional default 50
 *	@param pgno long int Paging Index [memory] optional default 1
 *	@param total long int Paging Total [memory] optional default false
 *
 *	@return post array Post information [memory]
 *	@return name string Post title [memory]
 *	@return postid long int Post ID [memory]
 *	@return admin integer Is admin [memory]
 *	@return cmntadmin integer Is comment admin [memory]
 *	@return pgsz long int Paging Size [memory]
 *	@return pgno long int Paging Index [memory] 
 *	@return total long int Paging Total [memory] 
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class PostInfoWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'optional' => array('keyid' => false, 'pname' => false, 'name' => '', 'id' => 0, 'postid' => false, 'pgsz' => 50, 'pgno' => 0, 'total' => false)
		); 
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['postid'] = $memory['postid'] ? $memory['postid'] : $memory['id'];
		$memory['pname'] = $memory['pname'] ? $memory['pname'] : $memory['name'];
		
		$workflow = array(
		array(
			'service' => 'transpera.entity.info.workflow',
			'input' => array('id' => 'postid', 'parent' => 'boardid', 'cname' => 'name', 'pname' => 'bname'),
			'conn' => 'cbdconn',
			'relation' => '`posts`',
			'type' => 'post',
			'sqlcnd' => "where `postid`=\${id}",
			'errormsg' => 'Invalid Post ID',
			'successmsg' => 'Post information given successfully',
			'output' => array('entity' => 'post')
		),
		array(
			'service' => 'display.comment.list.workflow',
			'output' => array('admin' => 'cmntadmin')
		));
		
		return Snowblozm::execute($workflow, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('post', 'pname', 'postid', 'chain', 'admin', 'comments', 'cmntadmin', 'total', 'pgsz', 'pgno');
	}
	
}

?>