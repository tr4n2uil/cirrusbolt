<?php 
require_once(SBSERVICE);

/**
 *	@class FileRmdirService
 *	@desc Removes empty directory at specified destination
 *
 *	@param directory string Directory path [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *	
**/
class FileRmdirService implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('directory')
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$directory = $memory['directory'];
		
		if (!@rmdir($directory)){
			$memory['valid'] = false;
			$memory['msg'] = "Unable to Remove Directory / Non Empty Directory";
			$memory['status'] = 505;
			$memory['details'] = 'Error removing directory : '.$directory.' @file.rmdir.service';
			return $memory;
		}
		
		$memory['valid'] = true;
		$memory['msg'] = 'Directory Removed Successfully';
		$memory['status'] = 200;
		$memory['details'] = 'Successfully executed';
		return $memory;
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array();
	}
	
}

?>