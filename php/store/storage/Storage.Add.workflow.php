<?php 
require_once(SBSERVICE);

/**
 *	@class StorageAddWorkflow
 *	@desc Adds new storage to space
 *
 *	@param filename string File name [memory] optional default false
 *	@param name string File name [memory] optional default 'storage'
 *	@param ext string File extension [memory] optional default 'file'
 *	@param mime string MIME type [memory] optional default 'application/force-download'
 *	@param size long int Size in bytes [memory] optional default 0
 *	@param keyid long int Usage Key ID [memory]
 *	@param spaceid long int Space ID [memory] optional default 0
 *	@param level integer Web level [memory] optional default 1 (space admin access allowed)
 *	@param owner long int Owner Key ID [memory] optional default keyid
 *	@param filekey string File key [memory] optional default false
 *
 *	@return stgid long int Storage ID [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class StorageAddWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('keyid'),
			'optional' => array('spaceid' => 0, 'level' => 1, 'owner' => false, 'filename' => false, 'name' => 'storage', 'ext' => 'file', 'mime' => 'application/force-download', 'size' => 0, 'filekey' => false)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$construct = false;
		if($memory['filename'] == false){
			$memory['filename'] = $memory['name'].'.'.$memory['ext'];
		}
		
		if($memory['filekey']){
			$construct = array(
			array(
				'service' => 'store.space.info.workflow'
			),
			array(
				'service' => 'cbcore.file.upload.service',
				'input' => array('path' => 'sppath', 'key' => 'filekey')
			));
		}
	
		$service = array(
			'service' => 'transpera.entity.add.workflow',
			'args' => array('filename', 'mime', 'size'),
			'input' => array('parent' => 'spaceid'),
			'conn' => 'cbconn',
			'relation' => '`storages`',
			'sqlcnd' => "(`stgid`, `owner`, `stgname`, `filename`, `mime`, `size`) values (\${id}, \${owner}, '\${filename}',  '\${filename}', '\${mime}', \${size})",
			'escparam' => array('stgname', 'filename', 'mime'),
			'successmsg' => 'Storage added successfully',
			'output' => array('id' => 'stgid'),
			'construct' => $construct,
			'type' => 'storage'
		);
		
		return Snowblozm::run($service, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('stgid');
	}
	
}

?>