<?php 
require_once(SBSERVICE);

/**
 *	@class SpaceInfoWorkflow
 *	@desc Returns information for space using ID
 *
 *	@param spaceid long int Space ID [memory]
 *	@param keyid long int Usage Key ID [memory] optional default false
 *	@param cntrid long int Container ID [memory] optional default 0
 *	@param cntrname string Container name [memory] optional default ''
 *
 *	@return spaceid string Space ID [memory]
 *	@return cntrname string Container name [memory]
 *	@return cntrid long int Container ID [memory]
 *	@return spname string Space name [memory]
 *	@return sppath string Space path [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class SpaceInfoWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('spaceid'),
			'optional' => array('keyid' => false, 'cntrname' => '', 'cntrid' => 0)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['msg'] = 'Space information given successfully';
		
		$workflow = array(
		array(
			'service' => 'transpera.entity.info.workflow',
			'input' => array('id' => 'spaceid', 'parent' => 'cntrid'),
			'conn' => 'tsconn',
			'relation' => '`spaces`',
			'sqlcnd' => "where `spaceid`=\${id}",
			'errormsg' => 'Invalid Space ID',
			'output' => array('entity' => 'space')
		),
		array(
			'service' => 'cbcore.data.select.service',
			'args' => array('space'),
			'params' => array('space.spname' => 'spname', 'space.sppath' => 'sppath')
		));
		
		return Snowblozm::execute($workflow, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('spaceid', 'cntrid', 'cntrname', 'spname', 'sppath');
	}
	
}

?>