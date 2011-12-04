<?php 
require_once(SBSERVICE);

/**
 *	@class EntityListWorkflow
 *	@desc Returns all entity information in parent
 *
 *	@param relation string Relation name [memory]
 *	@param sqlcnd string SQL condition [memory]
  *	@param sqlprj string SQL projection [memory] optional default *
 *	@param keyid long int Usage Key ID [memory]
 *	@param user string User email [memory]
 *	@param id long int Parent ID [memory] optional default 0
 *	@param pname long int Parent name [memory] optional default ''
 *	@param action string Action to authorize [memory] optional default 'edit'
 *	@param successmsg string Success message [memory] optional default 'Entity information successfully'
 *
 *	@param conn array DataService instance configuration key [memory]
 *
 *	@return entities long int Entities information [memory]
 *	@return pname string Parent name [memory]
 *	@return id long int Parent ID [memory]
 *	@return admin integer Is admin [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class EntityListWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('conn', 'keyid', 'user', 'id', 'relation', 'sqlcnd'),
			'optional' => array('pname' => '', 'action' => 'edit', 'sqlprj' => '*', 'successmsg' => 'Entities information given successfully')
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['msg'] = $memory['successmsg'];
		
		$workflow = array(
		array(
			'service' => 'transpera.reference.children.workflow'
		),
		array(
			'service' => 'cbcore.data.list.service',
			'args' => array('children'),
			'attr' => 'child',
			'default' => array(-1),
		),
		array(
			'service' => 'transpera.relation.select.workflow',
			'args' => array('list'),
			'escparam' => array('list'),
			'check' => false,
			'output' => array('result' => 'entities')
		),
		array(
			'service' => 'transpera.reference.authorize.workflow',
			'admin' => true,
		));
		
		return Snowblozm::execute($workflow, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('entities', 'id', 'pname', 'admin');
	}
	
}

?>