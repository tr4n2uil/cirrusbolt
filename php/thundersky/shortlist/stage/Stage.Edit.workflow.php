<?php 
require_once(SBSERVICE);

/**
 *	@class StageEditWorkflow
 *	@desc Edits stage for event using ID
 *
 *	@param stageid long int Stage ID [memory]
 *	@param name string Stage name [memory]
 *	@param stage integer Stage number [memory]
 *	@param open integer Is open [memory]
 *	@param start string Start time [memory] (YYYY-MM-DD hh:mm:ss format)
 *	@param end string End time [memory] (YYYY-MM-DD hh:mm:ss format)
 *	@param status integer Status [memory]
 *	@param keyid long int Usage Key ID [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class StageEditWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('keyid', 'stageid', 'name', 'stage', 'start', 'end', 'open', 'status')
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$service = array(
			'service' => 'transpera.entity.edit.workflow',
			'input' => array('id' => 'stageid'),
			'args' => array('name', 'stage', 'start', 'end', 'open', 'status'),
			'conn' => 'cbslconn',
			'relation' => '`stages`',
			'sqlcnd' => "set `name`='\${name}', `stage`=\${stage}, `start`='\${start}', `end`='\${end}', `open`=\${open}, `status`=\${status} where `stageid`=\${id}",
			'escparam' => array('name', 'start', 'end'),
			'successmag' => 'Stage edited successfully'
		);
		
		return Snowblozm::run($service, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array();
	}
	
}

?>