<?php 
require_once(SBSERVICE);

/**
 *	@class RelationUniqueWorkflow
 *	@desc Executes SELECT query on relation returning unique result, if any, in resultset
 *
 *	@param relation string Relation name [memory]
 *	@param sqlcnd string SQL condition [memory]
 *	@param sqlprj string SQL projection [memory] optional default *
 *	@param args array Query parameters [args]
 *	@param escparam array Escape parameters [memory] optional default array()
 *	@param check boolean Is validate [memory] optional default true
 *	@param not boolean Value check nonequal [memory] optional default true
 *	@param errormsg string Error message [memory] optional default 'Error in Database'
 *	@param errstatus integer Error status code [memory] optional default 505
 *
 *	@param conn array DataService instance configuration key [memory]
 *
 *	@return result array/attribute Result tuple or value [memory] 
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class RelationUniqueWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('conn', 'relation', 'sqlcnd'),
			'optional' => array('sqlprj' => '*', 'escparam' => array(), 'errormsg' => 'Error in Database', 'not' => true, 'errstatus' => 505, 'check' => true)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$service = array(
			'service' => 'rdbms.query.execute.workflow',
			'args' => $memory['args'],
			'output' => array('sqlresult' => 'result'),
			'query' => 'select '.$memory['sqlprj'].' from '.$memory['relation'].' '.$memory['sqlcnd'].';'
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