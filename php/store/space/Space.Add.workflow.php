<?php 
require_once(SBSERVICE);

/**
 *	@class SpaceAddWorkflow
 *	@desc Adds new space to container
 *
 *	@param spname string Space name [memory]
 *	@param sppath string Space path [memory]
 *	@param keyid long int Usage Key ID [memory]
 *	@param cntrid long int Container ID [memory] optional default 0
 *	@param level integer Web level [memory] optional default 1 (container admin access allowed)
 *	@param owner long int Owner Key ID [memory] optional default keyid
 *
 *	@return spaceid long int Space ID [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class SpaceAddWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('keyid', 'spname', 'sppath'),
			'optional' => array('cntrid' => 0, 'level' => 1, 'owner' => false)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['owner'] = $memory['owner'] ? $memory['owner'] : $memory['keyid'];
		$memory['msg'] = 'Space added successfully';
		
		$workflow = array(
		array(
			'service' => 'transpera.reference.add.workflow',
			'input' => array('parent' => 'cntrid'),
			'type' => 'space',
			'output' => array('id' => 'spaceid')
		),
		array(
			'service' => 'cbcore.file.mkdir.service',
			'input' => array('directory' => 'sppath')
		),
		array(
			'service' => 'transpera.relation.insert.workflow',
			'args' => array('spaceid', 'owner', 'spname', 'sppath'),
			'conn' => 'cbconn',
			'relation' => '`spaces`',
			'sqlcnd' => "(`spaceid`, `owner`, `spname`, `sppath`) values (\${spaceid}, \${owner}, '\${spname}', '\${sppath}')",
			'escparam' => array('spname', 'sppath')
		));
		
		return Snowblozm::execute($workflow, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('spaceid');
	}
	
}

?>