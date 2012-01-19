<?php 
require_once(SBSERVICE);

/**
 *	@class StorageWriteWorkflow
 *	@desc Uploads file and its storage information
 *
 *	@param stgid long int Storage ID [memory]
 *	@param keyid long int Usage Key ID [memory]
 *	@param filekey string File key [memory] optional default 'storage'
 *	@param mime string MIME type [memory] optional default 'application/force-download'
 *	@param maxsize long int Maximum size [memory] optional default false
 *	@param spaceid long int Space ID [memory] optional default 0
 *
 *	@return filename string Filename received [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class StorageWriteWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('keyid', 'stgid'),
			'optional' => array('spaceid' => 0, 'maxsize' => false, 'mime' => 'application/force-download', 'filekey' => 'storage')
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['msg'] = 'Storage written successfully';
		
		$workflow = array(
		array(
			'service' => 'transpera.reference.authorize.workflow',
			'input' => array('id' => 'stgid'),
			'action' => 'edit'
		),
		array(
			'service' => 'store.space.info.workflow',
			'output' => array('sppath' => 'path')
		),
		array(
			'service' => 'store.storage.info.workflow',
			'output' => array('filename' => 'name')
		),
		array(
			'service' => 'cbcore.file.upload.service',
			'input' => array('key' => 'filekey')
		),
		array(
			'service' => 'transpera.relation.update.workflow',
			'args' => array('stgid', 'filename', 'mime', 'size'),
			'conn' => 'tsconn',
			'relation' => '`storages`',
			'sqlcnd' => "set `stgname`='\${filename}', `mime`='\${mime}', `size`=\${size} where `stgid`=\${stgid}",
			'escparam' => array('mime', 'filename')
		),
		array(
			'service' => 'gauge.track.write.workflow',
			'input' => array('id' => 'stgid')
		));
		
		return Snowblozm::execute($workflow, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('filename');
	}
	
}

?>