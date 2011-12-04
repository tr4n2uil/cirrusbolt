<?php 
require_once(SBSERVICE);

/**
 *	@class RelationSelectWorkflow
 *	@desc Executes SELECT query on relation returning all results in resultset
 *
 *	@param relation string Relation name [memory]
 *	@param sqlcnd string SQL condition [memory]
 *	@param sqlprj string SQL projection [memory] optional default *
 *	@param args array Query parameters [args]
 *	@param escparam array Escape parameters [memory] optional default array()
 *	@param check boolean Is validate [memory] optional default true
 *	@param errormsg string Error message [memory] optional default 'Error in Database'
 *
 *	@param conn array DataService instance configuration key [memory]
 *
 *	@return result array Resultset [memory] 
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class RelationSelectWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('conn', 'relation', 'sqlcnd'),
			'optional' => array('sqlprj' => '*', 'escparam' => array(), 'errormsg' => 'Error in Database', 'check' => true)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$relation = $memory['relation'];

		$service = array(
			'service' => 'rdbms.query.execute.workflow',
			'args' => $memory['args'],
			'output' => array('sqlresult' => 'result'),
			'query' => 'select '.$memory['sqlprj'].' from '.$relation.' '.$memory['sqlcnd'].';',
			'count' => 0,
			'not' => false
		);
		
		return Snowblozm::run($service, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('result');
	}
	
}

?>