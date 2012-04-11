<?php 
require_once(SBSERVICE);

/**
 *	@class FileListWorkflow
 *	@desc Returns all files information in directory
 *
 *	@param keyid long int Usage Key ID [memory]
 *	@param dirid long int Directory ID [memory] optional default 0
 *	@param dirname string Directory name [memory] optional default ''
 *	@param dirpath string Directory path [memory] optional default 'storage/directory/'
 *
 *	@return files array File information [memory]
 *	@return dirid long int Directory ID [memory]
 *	@return dirname string Directory name [memory]
 *	@return dirpath string Directory path [memory]
 *	@return admin integer Is admin [memory] 
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class FileListWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('keyid'),
			'optional' => array('dirid' => false, 'id' => 0, 'dirname' => false, 'name' => '', 'dirpath' => 'storage/directory/', 'pgsz' => 25, 'pgno' => 0, 'total' => false),
			'set' => array('id', 'name')
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['dirid'] = $memory['dirid'] ? $memory['dirid'] : $memory['id'];
		$memory['dirname'] = $memory['dirname'] ? $memory['dirname'] : $memory['name'];
		
		$service = array(
			'service' => 'transpera.entity.list.workflow',
			'input' => array('id' => 'dirid', 'pname' => 'dirname'),
			'conn' => 'cbsconn',
			'relation' => '`files`',
			'type' => 'file',
			'sqlprj' => '`fileid`, `name`, `mime`, `size`',
			'sqlcnd' => "where `fileid` in \${list} order by `name`",
			'output' => array('entities' => 'files'),
			'successmsg' => 'Files information given successfully',
			'mapkey' => 'fileid',
			'mapname' => 'file',
			'saction' => 'add'
		);
		
		return Snowblozm::run($service, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('files', 'admin', 'dirid', 'dirname', 'dirpath', 'id', 'padmin', 'pchain', 'total', 'pgno', 'pgsz');
	}
	
}

?>