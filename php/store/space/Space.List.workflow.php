<?php 
require_once(SBSERVICE);

/**
 *	@class SpaceListWorkflow
 *	@desc Returns all spaces information in container
 *
 *	@param keyid long int Usage Key ID [memory]
 *	@param cntrid long int Container ID [memory] optional default 0
 *	@param cntrname string Container name [memory] optional default ''
 *
 *	@return spaces array Space information [memory]
 *	@return cntrid long int Container ID [memory]
 *	@return cntrname string Container name [memory]
 *	@return admin integer Is admin [memory] 
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class SpaceListWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('keyid'),
			'optional' => array('cntrid' => 0, 'cntrname' => '')
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$service = array(
			'service' => 'transpera.enitity.list.workflow',
			'input' => array('id' => 'cntrid'),
			'conn' => 'cbconn',
			'relation' => '`spaces`',
			'sqlprj' => '`spaceid`, `owner`, `spname`',
			'sqlcnd' => "where `spaceid` in \${list} order by `spname`",
			'output' => array('entities' => 'spaces'),
			'successmsg' => 'Spaces information given successfully'
		);
		
		return Snowblozm::run($service, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('spaces', 'admin', 'cntrid', 'cntrname');
	}
	
}

?>