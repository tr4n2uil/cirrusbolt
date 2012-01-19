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
 *	@param pgsz long int Paging Size [memory] optional default false
 *	@param pgno long int Paging Index [memory] optional default 1
 *	@param total long int Paging Total [memory] optional default false
 *
 *	@param conn array DataService instance configuration key [memory]
 *
 *	@return result array Resultset [memory] 
 *	@return total long int Paging total [memory]
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
			'optional' => array('sqlprj' => '*', 'escparam' => array(), 'errormsg' => 'Error in Database', 'check' => true, 'pgsz' => false, 'pgno' => 0, 'total' => false)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$relation = $memory['relation'];
		$pgsz = $memory['pgsz'];
		$limit = '';
		
		if($pgsz){
			if(!$memory['total']){
				$service = array(
					'service' => 'rdbms.query.execute.workflow',
					'args' => $memory['args'],
					'output' => array('sqlresult' => 'result'),
					'query' => 'select count(*) as total from '.$relation.' '.$memory['sqlcnd'].';',
					'count' => 0,
					'not' => false
				);
				
				$memory = Snowblozm::run($service, $memory);
				if(!$memory['valid'])
					return $memory;
				
				$memory['total'] = $memory['result'][0]['total'];
			}
			
			$limit = ' limit '.($pgsz*$memory['pgno']).','.$pgsz;
		}
		
		$service = array(
			'service' => 'rdbms.query.execute.workflow',
			'args' => $memory['args'],
			'output' => array('sqlresult' => 'result'),
			'query' => 'select '.$memory['sqlprj'].' from '.$relation.' '.$memory['sqlcnd'].$limit.';',
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