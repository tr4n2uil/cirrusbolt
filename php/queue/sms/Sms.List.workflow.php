<?php 
require_once(SBSERVICE);

/**
 *	@class SmsListWorkflow
 *	@desc Returns all sms information in queue
 *
 *	@param keyid long int Usage Key ID [memory]
 *	@param queid/id long int Queue ID [memory] optional default 0
 *
 *	@param pgsz long int Paging Size [memory] optional default false
 *	@param pgno long int Paging Index [memory] optional default 1
 *	@param total long int Paging Total [memory] optional default false
 *
 *	@param user string User email [memory] optional default 'unknown@sms.list'
 *
 *	@return sms array SMS information [memory]
 *	@return queid long int Queue ID [memory]
 *	@return admin integer Is admin [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class SmsListWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('keyid'),
			'optional' => array('queid' => 0, 'pgsz' => false, 'pgno' => 0, 'total' => false, 'user' => 'unknown@sms.list', 'id' => 0)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['queid'] = $memory['queid'] ? $memory['queid'] : $memory['id'];
		
		$service = array(
			'service' => 'transpera.entity.list.workflow',
			'input' => array('id' => 'queid'),
			'conn' => 'cbqconn',
			'relation' => '`sms`',
			'sqlprj' => '`smsid`, `to`, `from`, `status`, `stime`, substring(`body`, 0, 50) as `body`',
			'sqlcnd' => "where `smsid` in \${list} order by `status` asc, `smsid` desc",
			'type' => 'sms',
			'successmsg' => 'SMS information given successfully',
			'output' => array('entities' => 'sms'),
			'mapkey' => 'smsid',
			'mapname' => 'sms'
		);
		
		return Snowblozm::run($service, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('sms', 'queid', 'admin');
	}
	
}

?>