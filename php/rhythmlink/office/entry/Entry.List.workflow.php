<?php 
require_once(SBSERVICE);

/**
 *	@class EntryListWorkflow
 *	@desc Returns all entries in archive
 *
 *	@param keyid long int Usage Key ID [memory]
 *	@param arcid long int Archive ID [memory] optional default 0
 *	@param arcname string Archive name [memory] optional default ''
 *	@param pgsz long int Paging Size [memory] optional default false
 *	@param pgno long int Paging Index [memory] optional default 1
 *	@param total long int Paging Total [memory] optional default false
 *
 *	@return entries array Entries information [memory]
 *	@return arcid long int Archive ID [memory]
 *	@return arcname string Archive name [memory]
 *	@return admin integer Is admin [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class EntryListWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('keyid'),
			'optional' => array('arcid' => 0, 'arcname' => '', 'pgsz' => false, 'pgno' => 0, 'total' => false)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['msg'] = 'Entries information given successfully';
		
		$workflow = array(
		array(
			'service' => 'transpera.reference.authorize.workflow',
			'input' => array('id' => 'arcid'),
			'action' => 'list'
		),
		array(
			'service' => 'transpera.relation.select.workflow',
			'args' => array('arcid'),
			'conn' => 'rtconn',
			'relation' => '`entries`',
			'sqlcnd' => "where `arcid`=\${arcid}",
			'check' => false,
			'output' => array('result' => 'entries'),
		),
		array(
			'service' => 'transpera.reference.authorize.workflow',
			'input' => array('id' => 'arcid'),
			'admin' => true,
			'action' => 'add'
		));
		
		return Snowblozm::run($service, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('entries', 'arcid', 'arcname', 'admin');
	}
	
}

?>