<?php 
require_once(SBSERVICE);

/**
 *	@class RelationRemoveWorkflow
 *	@desc Removes or updates relation between two persons
 *
 *	@param to long int Person ID [memory]
 *	@param keyid long int Usage Key ID [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class RelationRemoveWorkflow implements Service {
	
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
			$memory['msg'] = 'Invalid Relation';
			$memory['status'] = 500;
			$memory['details'] = "Relation invalid between from=".$memory['pnid']." and to=".$memory['to']." @people.relation.add";
			return $memory;
		}
			
		$state = $memory['result'][0]['state'];
		
		switch($state){
			case 'F' :
				$memory['valid'] = true;
				$memory['msg'] = 'Relation removed successfully';
				$memory['status'] = 200;
				$memory['details'] = "Relation F existed between from=".$memory['pnid']." and to=".$memory['to']." @people.relation.add";
				
				$workflow = array(
				array(
					'service' => 'transpera.relation.delete.workflow',
					'args' => array('pnid', 'to'),
					'conn' => 'rtconn',
					'relation' => '`relations`',
					'sqlcnd' => "where (`from`=\${pnid} and `to`=\${to}) or (`from`=\${to} and `to`=\${from})",
					'errormsg' => 'Invalid Relation ID',
					'count' => 2
				));
				break;
			
			case 'R' :
				$memory['valid'] = true;
				$memory['msg'] = 'Relation rejected successfully';
				$memory['status'] = 200;
				$memory['details'] = "Relation R existed between from=".$memory['pnid']." and to=".$memory['to']." @people.relation.add";
				
				$workflow = array(
				array(
					'service' => 'transpera.relation.update.workflow',
					'args' => array('pnid', 'to'),
					'conn' => 'rtconn',
					'relation' => '`relations`',
					'sqlcnd' => "set `state`='J' where `from`=\${pnid} and `to`=\${to}",
					'errormsg' => 'Invalid Relation ID',
				),
				array(
					'service' => 'transpera.relation.update.workflow',
					'args' => array('pnid', 'to'),
					'conn' => 'rtconn',
					'relation' => '`relations`',
					'sqlcnd' => "set `state`='J' where `from`=\${to} and `to`=\${from}",
					'errormsg' => 'Invalid Relation ID',
				));
				break;
				
			case 'C' :
				$memory['valid'] = true;
				$memory['msg'] = 'Forward relation removed successfully';
				$memory['status'] = 200;
				$memory['details'] = "Relation C existed between from=".$memory['pnid']." and to=".$memory['to']." @people.relation.add";
				
				$workflow = array(
				array(
					'service' => 'transpera.relation.update.workflow',
					'args' => array('pnid', 'to'),
					'conn' => 'rtconn',
					'relation' => '`relations`',
					'sqlcnd' => "set `state`='R' where `from`=\${pnid} and `to`=\${to}",
					'errormsg' => 'Invalid Relation ID',
				),
				array(
					'service' => 'transpera.relation.update.workflow',
					'args' => array('pnid', 'to'),
					'conn' => 'rtconn',
					'relation' => '`relations`',
					'sqlcnd' => "set `state`='F' where `from`=\${to} and `to`=\${from}",
					'errormsg' => 'Invalid Relation ID',
				));
				break;
			
			case 'B' :
				$memory['valid'] = false;
				$memory['msg'] = 'Relation is blocked by the other person';
				$memory['status'] = 500;
				$memory['details'] = "Relation B exists between from=".$memory['pnid']." and to=".$memory['to']." @people.relation.add";
				return $memory;
				break;
			
			case 'U' :
				$memory['valid'] = true;
				$memory['msg'] = 'Relation unblocked successfully';
				$memory['status'] = 200;
				$memory['details'] = "Relation U existed between from=".$memory['pnid']." and to=".$memory['to']." @people.relation.add";
				
				$workflow = array(
				array(
					'service' => 'transpera.relation.delete.workflow',
					'args' => array('pnid', 'to'),
					'conn' => 'rtconn',
					'relation' => '`relations`',
					'sqlcnd' => "where (`from`=\${pnid} and `to`=\${to}) or (`from`=\${to} and `to`=\${from})",
					'errormsg' => 'Invalid Relation ID',
					'count' => 2
				));
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