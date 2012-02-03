<?php 
require_once(SBSERVICE);

/**
 *	@class RelationAddWorkflow
 *	@desc Adds or updates relation between two persons
 *
 *	@param to long int Person ID [memory]
 *	@param keyid long int Usage Key ID [memory]
 *
 *	@states : F=forward R=reverse C=complete B=blocked J=rejected
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class RelationAddWorkflow implements Service {
	
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
		if(!isset($memory['pnid']))
			return $memory;
		
		if($memory['valid']){
			$state = $memory['result'][0]['state'];
			
			switch($state){
				case 'F' :
					$memory['valid'] = true;
					$memory['msg'] = 'Relation already exists and is forward';
					$memory['status'] = 200;
					$memory['details'] = "Relation F already exists between from=".$memory['pnid']." and to=".$memory['to']." @people.relation.add";
					return $memory;
					break;
				
				case 'R' :
					$memory['valid'] = true;
					$memory['msg'] = 'Relation completed successfully';
					$memory['status'] = 200;
					$memory['details'] = "Relation C exists between from=".$memory['pnid']." and to=".$memory['to']." @people.relation.add";
					
					$workflow = array(
					array(
						'service' => 'transpera.relation.update.workflow',
						'args' => array('pnid', 'to'),
						'conn' => 'rtconn',
						'relation' => '`relations`',
						'sqlcnd' => "set `state`='C' where `from`=\${pnid} and `to`=\${to}",
						'errormsg' => 'Invalid Relation ID',
					),
					array(
						'service' => 'transpera.relation.update.workflow',
						'args' => array('pnid', 'to'),
						'conn' => 'rtconn',
						'relation' => '`relations`',
						'sqlcnd' => "set `state`='C' where `from`=\${to} and `to`=\${pnid}",
						'errormsg' => 'Invalid Relation ID',
					));
					break;
					
				case 'C' :
					$memory['valid'] = true;
					$memory['msg'] = 'Relation already exists and is complete';
					$memory['status'] = 200;
					$memory['details'] = "Relation C already exists between from=".$memory['pnid']." and to=".$memory['to']." @people.relation.add";
					return $memory;
					break;
				
				case 'B' :
					$memory['valid'] = false;
					$memory['msg'] = 'Relation is blocked by the other person';
					$memory['status'] = 500;
					$memory['details'] = "Relation B exists between from=".$memory['pnid']." and to=".$memory['to']." @people.relation.add";
					return $memory;
					break;
				
				case 'J' :
					$memory['valid'] = true;
					$memory['msg'] = 'Forward relation completed successfully';
					$memory['status'] = 200;
					$memory['details'] = "Relation J exists between from=".$memory['pnid']." and to=".$memory['to']." @people.relation.add";
					
					$workflow = array(
					array(
						'service' => 'transpera.relation.update.workflow',
						'args' => array('pnid', 'to'),
						'conn' => 'rtconn',
						'relation' => '`relations`',
						'sqlcnd' => "set `state`='F' where `from`=\${pnid} and `to`=\${to}",
						'errormsg' => 'Invalid Relation ID',
					),
					array(
						'service' => 'transpera.relation.update.workflow',
						'args' => array('pnid', 'to'),
						'conn' => 'rtconn',
						'relation' => '`relations`',
						'sqlcnd' => "set `state`='R' where `from`=\${to} and `to`=\${pnid}",
						'errormsg' => 'Invalid Relation ID',
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
		}
		else {
			$memory['valid'] = true;
			$memory['msg'] = 'Forward relation added successfully';
			
			$workflow = array(
			array(
				'service' => 'transpera.relation.insert.workflow',
				'args' => array('pnid', 'to'),
				'conn' => 'rtconn',
				'relation' => '`relations`',
				'sqlcnd' => "(`from`, `to`, `state`) values (\${pnid}, \${to}, 'F')",
			),
			array(
				'service' => 'transpera.relation.insert.workflow',
				'args' => array('pnid', 'to'),
				'conn' => 'rtconn',
				'relation' => '`relations`',
				'sqlcnd' => "(`from`, `to`, `state`) values (\${to}, \${pnid}, 'R')",
			));
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