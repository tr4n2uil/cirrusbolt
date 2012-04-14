<?php 
require_once(SBSERVICE);

/**
 *	@class PuzzleInfoWorkflow
 *	@desc Returns puzzle information by ID
 *
 *	@param pzlid/id long int Puzzle ID [memory]
 *	@param keyid long int Usage Key ID [memory] optional default false
 *	@param user string Key User [memory]
 *	@param quizid long int Quiz ID [memory] optional default 0
 *	@param qzname/name string Quiz name [memory] optional default ''
 *
 *	@return puzzle array Puzzle information [memory]
 *	@return qzname string Quiz name [memory]
 *	@return quizid long int Quiz ID [memory]
 *	@return admin integer Is admin [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class PuzzleInfoWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('pzlid'),
			'optional' => array('keyid' => false, 'user' => '', 'qzname' => false, 'name' => '', 'quizid' => false, 'id' => 0),
			'set' => array('id', 'name')
		); 
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['pzlid'] = $memory['pzlid'] ? $memory['pzlid'] : $memory['id'];
		
		$service = array(
			'service' => 'transpera.entity.info.workflow',
			'input' => array('id' => 'pzlid', 'parent' => 'quizid', 'cname' => 'name', 'pname' => 'qzname'),
			'conn' => 'ayconn',
			'relation' => '`puzzles`',
			'sqlcnd' => "where `pzlid`=\${id}",
			'errormsg' => 'Invalid Puzzle ID',
			'type' => 'puzzle',
			'successmsg' => 'Puzzle information given successfully',
			'output' => array('entity' => 'puzzle')
		);
		
		return Snowblozm::run($service, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('puzzle', 'qzname', 'quizid', 'admin');
	}
	
}

?>