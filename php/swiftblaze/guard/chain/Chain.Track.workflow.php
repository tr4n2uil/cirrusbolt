<?php 
require_once(SBSERVICE);

/**
 *	@class ChainTrackWorkflow
 *	@desc Tracks chain activity
 *
 *	@param child long int Child ID [memory]
 *	@param cname string Child name [memory] optional default ''
 *	@param parent long int Parent ID [memory] optional default -1
 *	@param pname string Parent name [memory] optional default ''
 *	@param keyid long int Key ID [memory]
 *	@param user string Key Username [memory]
 *	@param action string Action value [memory] optional default 'info'
 *	@param type string Type name [memory] optional default 'general'
 *	@param verb string Activity verb [memory] optional default 'viewed'
 *	@param join string Activity join [memory] optional default 'in'
 *	@param public integer Public log [memory] optional default 1
 *
 *	@return return id long int Track ID [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class ChainTrackWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('child', 'keyid', 'user'),
			'optional' => array('cname' => '', 'parent' => -1, 'pname' => '', 'action' => 'info', 'verb' => 'viewed', 'join' => 'in', 'type' => 'general', 'public' => 1)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['msg'] = 'Chain Activity Tracked Successfully';
		$memory['ipaddr'] = $_SERVER['REMOTE_ADDR'];
		$memory['server'] = json_encode($_SERVER);
		
		$service = array(
			'service' => 'transpera.relation.insert.workflow',
			'args' => array('parent', 'child', 'keyid', 'user', 'action', 'type', 'cname', 'pname', 'verb', 'join', 'ipaddr', 'server', 'public'),
			'conn' => 'cbconn',
			'relation' => '`tracks`',
			'sqlcnd' => "(`parent`, `child`, `keyid`, `user`, `action`, `type`, `cname`, `pname`, `verb`, `join`, `ipaddr`, `public`, `ttime`, `server`) values (\${parent}, \${child}, \${keyid}, '\${user}', '\${action}', '\${type}', '\${cname}', '\${pname}', '\${verb}', '\${join}', '\${ipaddr}', \${public}, now(), '\${server}')",
			'escparam' => array('user', 'action', 'type', 'cname', 'pname', 'verb', 'join', 'ipaddr', 'server')
		);
		
		return Snowblozm::run($service, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('id');
	}
	
}

?>