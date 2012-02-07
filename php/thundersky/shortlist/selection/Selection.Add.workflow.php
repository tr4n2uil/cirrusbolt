<?php 
require_once(SBSERVICE);

/**
 *	@class SelectionAddWorkflow
 *	@desc Adds new selection for shortlist
 *
 *	@param stageid long int Stage ID [memory]
 *	@param refer long int Refer ID [memory]
 *	@param status integer Status [memory] optional default 1
 *	@param keyid long int Usage Key ID [memory]
 *	@param user string Key User [memory]
 *	@param owner long int Owner Key ID [memory] optional default keyid
 *	@param shlstid long int Shortlist ID [memory] optional default 0
 *	@param level integer Web level [memory] optional default false (inherit shortlist admin access)
 *
 *	@return selid long int Selection ID [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class SelectionAddWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('keyid', 'user', 'refer', 'stageid'),
			'optional' => array('shlstid' => 0, 'level' => false, 'status' => 1, 'owner' => false)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$service = array(
			'service' => 'transpera.reference.add.workflow',
			'input' => array('parent' => 'shlstid'),
			'authorize' => 'edit:add:remove:list:qualify',
			'args' => array('stageid', 'status'),
			'conn' => 'cbslconn',
			'relation' => '`selections`',
			'sqlcnd' => "(`selid`, `owner`, `stageid`, `refer`, `status`) values (\${id}, \${owner}, \${stageid}, \${refer}, \${status})"
			'output' => array('id' => 'selid'),
			'successmsg' => 'Selection added successfully'
		);
		
		return Snowblozm::run($service, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('selid');
	}
	
}

?>