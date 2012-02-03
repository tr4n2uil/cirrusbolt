<?php 
require_once(SBSERVICE);

/**
 *	@class CommunityListWorkflow
 *	@desc Returns all community information in person container specific to role
 *
 *	@param keyid long int Usage Key ID [memory]
 *	@param pnid long int Person ID [memory] optional default 0
 *	@param pnname string Person name [memory] optional default ''
 *	@param rlid long int Role ID [memory] optional default false
 *	@param rlname string Role name [memory] optional default ''
 *	@param pgsz long int Paging Size [memory] optional default false
 *	@param pgno long int Paging Index [memory] optional default 1
 *	@param total long int Paging Total [memory] optional default false
 *
 *	@return communities array Communities information [memory]
 *	@return rlid long int Role ID [memory]
 *	@return rlname string Role name [memory]
 *	@return pnid long int Person ID [memory]
 *	@return pnname string Person name [memory]
 *	@return admin integer Is admin [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class CommunityListWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('keyid'),
			'optional' => array('pnid' => 0, 'pnname' => '', 'rlid' => false, 'rlname' => '',  'pgsz' => false, 'pgno' => 0, 'total' => false)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		if($memory['rlid']){
			$last = 'and `role`=\${rlid}';
			$args = array('rlid');
		}
		else {
			$last = '';
			$args = array();
		}
		
		$service = array(
			'service' => 'transpera.entity.list.workflow',
			'input' => array('id' => 'pnid'),
			'args' => $args,
			'conn' => 'rtconn',
			'relation' => '`communities`',
			'sqlcnd' => "where `rlid` in \${list} $last order by `priority` desc",
			'type' => 'community',
			'successmsg' => 'Community information given successfully',
			'output' => array('entities' => 'communities'),
		);
		
		return Snowblozm::run($service, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('communities', 'pnid', 'pnname', 'rlid', 'rlname', 'admin');
	}
	
}

?>