<?php 
require_once(SBSERVICE);

/**
 *	@class StageAddWorkflow
 *	@desc Adds new stage for shortlist
 *
 *	@param name string Stage name [memory]
 *	@param stage integer Stage number [memory]
 *	@param open integer Is open [memory] optional default 0
 *	@param start string Start time [memory] (YYYY-MM-DD hh:mm:ss format)
 *	@param end string End time [memory] (YYYY-MM-DD hh:mm:ss format)
 *	@param status integer Status [memory] optional default 1
 *	@param keyid long int Usage Key ID [memory]
 *	@param shlstid long int Shortlist ID [memory] optional default 0
 *	@param level integer Web level [memory] optional default 1 (shortlist admin access allowed)
 *
 *	@return stageid long int Stage ID [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class StageAddWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('keyid', 'name', 'stage', 'start', 'end'),
			'optional' => array('open' => 0, 'status' => 1, 'shlstid' => 0, 'level' => 1)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$service = array(
			'service' => 'transpera.entity.add.workflow',
			'input' => array('parent' => 'shlstid'),
			'output' => array('id' => 'stageid')
			'args' => array('name', 'stage', 'start', 'end', 'open', 'status'),
			'conn' => 'cbslconn',
			'relation' => '`stages`',
			'sqlcnd' => "(`stageid`, `owner`, `name`, `stage`, `start`, `end`, `open`, `status`) values (\${id}, \${owner}, '\${name}', \${stage}, '\${start}', '\${end}', \${open}, \${status})",
			'escparam' => array('name', 'start', 'end'),
			'successmsg' => 'Stage added successfully'
		);
		
		return Snowblozm::run($service, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('stageid');
	}
	
}

?>