<?php 
require_once(SBSERVICE);

/**
 *	@class SpaceFindWorkflow
 *	@desc Returns information for space using Name
 *
 *	@param spname long int Space name [memory]
 *
 *	@return spaceid string Space ID [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class SpaceFindWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('spname')
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['msg'] = 'Space found successfully';
		
		$workflow = array(
		array(
			'service' => 'transpera.relation.unique.workflow',
			'args' => array('spname'),
			'conn' => 'tsconn',
			'relation' => '`spaces`',
			'sqlcnd' => "where `spname`='\${spname}'",
			'escparam' => array('spname'),
			'errormsg' => 'Invalid Space ID'
		),
		array(
			'service' => 'cbcore.data.select.service',
			'args' => array('result'),
			'params' => array('result.0.spaceid' => 'spaceid')
		));
		
		return Snowblozm::execute($workflow, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('spaceid');
	}
	
}

?>