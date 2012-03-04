<?php 
require_once(SBSERVICE);

/**
 *	@class CommentListWorkflow
 *	@desc Returns all comments information in post
 *
 *	@param keyid long int Usage Key ID [memory]
 *	@param postid/id long int Post ID [memory] optional default 0
 *	@param pname/name string Post name [memory] optional default ''
 *
 *	@param pgsz long int Paging Size [memory] optional default false
 *	@param pgno long int Paging Index [memory] optional default 1
 *	@param total long int Paging Total [memory] optional default false
 *
 *	@return comments array Comments information [memory]
 *	@return postid long int Post ID [memory]
 *	@return pname string Post name [memory]
 *	@return admin integer Is admin [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class CommentListWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('keyid'),
			'optional' => array('postid' => false, 'id' => 0, 'pname' => false, 'name' => '', 'pgsz' => 50, 'pgno' => 0, 'total' => false)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['postid'] = $memory['postid'] ? $memory['postid'] : $memory['id'];
		$memory['pname'] = $memory['pname'] ? $memory['pname'] : $memory['name'];
		
		$service = array(
			'service' => 'transpera.entity.list.workflow',
			'input' => array('id' => 'postid', 'pname' => 'pname'),
			'conn' => 'cbdconn',
			'relation' => '`comments`',
			'type' => 'comment',
			'sqlprj' => '`cmtid`, substring(`comment`, 1, 512) as `comment`',
			'sqlcnd' => "where `cmtid` in \${list} order by `cmtid`",
			'successmsg' => 'Comments information given successfully',
			'lsttrack' => true,
			'output' => array('entities' => 'comments'),
			'mapkey' => 'cmtid',
			'mapname' => 'comment'
		);
		
		return Snowblozm::run($service, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('comments', 'postid', 'pname', 'admin', 'total', 'pgno', 'pgsz');
	}
	
}

?>