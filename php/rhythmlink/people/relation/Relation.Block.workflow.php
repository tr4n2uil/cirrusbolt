<?php 
require_once(SBSERVICE);

/**
 *	@class RelationBlockWorkflow
 *	@desc Blocks or unblocks relation between two persons
 *
 *	@param to long int Persons ID [memory]
 *	@param keyid long int Usage Key ID [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class RelationBlockWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('keyid', 'to')
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$workflow = array(
		array(
			'service' => 'people.person.info.workflow'
		),
		array(
			'service' => 'transpera.relation.unique.workflow',
			'args' => array('pnid', 'to'),
			'conn' => 'rtconn',
			'relation' => '`relations`',
			'sqlcnd' => "where `from`=\${pnid} and `to`=\${to}",
			'errormsg' => 'Invalid Relation'
		));
		
		$memory = Snowblozm::execute($workflow, $memory);
		
		if(!$memory['valid']){
			$memory['valid'] = false;
			$memory['msg'] = 'No relation to block';
			$memory['status'] = 500;
			$memory['details'] = "Relation invalid between from=".$memory['pnid']." and to=".$memory['to']." @people.relation.add";
			return $memory;
		}
			
		$state = $memory['result'][0]['state'];
		
		switch($state){
			case 'F' :
			case 'R' :
			case 'C' :
			case 'J' :
				$memory['valid'] = true;
				$memory['msg'] = 'Relation blocked successfully';
				$memory['status'] = 200;
				$memory['details'] = "Relation '$state' existed between from=".$memory['pnid']." and to=".$memory['to']." @people.relation.add";
				
				$workflow = array(
				array(
					'service' => 'transpera.relation.update.workflow',
					'args' => array('pnid', 'to'),
					'conn' => 'rtconn',
					'relation' => '`relations`',
					'sqlcnd' => "set `state`='U' where `from`=\${pnid} and `to`=\${to}",
					'errormsg' => 'Invalid Relation ID',
				),
				array(
					'service' => 'transpera.relation.update.workflow',
					'args' => array('pnid', 'to'),
					'conn' => 'rtconn',
					'relation' => '`relations`',
					'sqlcnd' => "set `state`='B' where `from`=\${to} and `to`=\${from}",
					'errormsg' => 'Invalid Relation ID',
				));
				break;
			
			case 'B' :
				$memory['valid'] = false;
				$memory['msg'] = 'Relation is already blocked by the other person';
				$memory['status'] = 500;
				$memory['details'] = "Relation B exists between from=".$memory['pnid']." and to=".$memory['to']." @people.relation.add";
				return $memory;
				break;
			
			default :
				$memory['valid'] = false;
				$memory['msg'] = 'Invalid Relation State';
				$memory['status'] = 500;
				$memory['details'] = "Relation state invalid between from=".$memory['pnid']." and to=".$memory['to']." @people.relation.add";
				return $memory;
				break;
		}
		
		return Snowblozm::execute($workflow, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array();
	}
	
}

?>