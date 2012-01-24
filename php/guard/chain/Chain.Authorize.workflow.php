<?php 
require_once(SBSERVICE);

/**
 *	@class ChainAuthorizeWorkflow
 *	@desc Authorizes key for chain operations and returns admin flag for action
 *
 *	@param chainid long int Chain ID [memory]
 *	@param keyid long int Key ID [memory]
 *
 *	@param cstate string State [memory] optional default false (true= Not '0')
 *	@param action string Action to authorize member [memory] optional default 'edit'
 *	@param state string State to authorize member [memory] optional default false (true= Not '0')
 *	@param iaction string Action to authorize inherit [memory] optional default 'edit'
 *	@param istate string State to authorize inherit [memory] optional default false (true= Not '0')
 *	@param init boolean init flag [memory] optional default true
 *	@param admin boolean Is return admin flag [memory] optional default false
 *
 *	@return admin boolean Is admin [memory]
 *	@return level integer Web level [memory]
 *	@return masterkey long int Master key ID [memory]
 *	@return authorize string Authorization Control [memory]
 *	@return state string State [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class ChainAuthorizeWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('keyid', 'chainid'),
			'optional' => array('level' => 0, 'action' => 'edit', 'iaction' => 'edit', 'cstate' => false, 'state' => false, 'istate' => false, 'admin' => false, 'init' => true)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		
		/**
		 *	@initialize chain query
		**/
		$last = '';
		$args = array('chainid');
		$escparam = array();

		if($memory['cstate'] === true){
			$last = " and `state`<>'0' ";
		}
		else if($memory['cstate']){
			$last = " and `state`='\${cstate}' ";
			array_push($escparam, 'cstate');
			array_push($args, 'cstate');
		}	
		
		/**
		 *	@check chain info
		**/
		$workflow = array(
		array(
			'service' => 'transpera.relation.unique.workflow',
			'args' => $args,
			'conn' => 'cbconn',
			'relation' => '`chains`',
			'sqlprj' => '`masterkey`, `level`, `authorize`, `state`',
			'sqlcnd' => "where `chainid`=\${chainid} $last",
			'escparam' => $escparam,
			'errormsg' => 'Invalid Chain ID'
		),
		array(
			'service' => 'cbcore.data.select.service',
			'args' => array('result'),
			'params' => array('result.0.masterkey' => 'masterkey', 'result.0.level' => 'level', 'result.0.authorize' => 'authorize', 'result.0.state' => 'state')
		));
		
		$memory = Snowblozm::execute($workflow, $memory);
		if(!$memory['valid'])
			return $memory;
		
		$memory['msg'] = 'Key authorized successfully';
		
		/**
		 *	@check masterkey, authorize
		**/
		if($memory['keyid'] == $memory['masterkey'] || strpos($memory['authorize'], $memory['action']) === false)
			return $memory;
		
		/**
		 *	@read level
		**/
		$level = $memory['level'];
		$memory['level'] = $level + 1;
		
		
		/**
		 *	@initialize chain query
		**/
		$last = $ilast = '';
		$args = array('keyid', 'chainid', 'action', 'iaction');
		$escparam = array('action', 'iaction');

		if($memory['state'] === true){
			$last = " and `state`<>'0' ";
		}
		else if($memory['state']){
			$last = " and `state`='\${state}' ";
			array_push($escparam, 'state');
			array_push($args, 'state');
		}		
		
		if($memory['istate'] === true){
			$ilast = " and `state`<>'0' ";
		}
		else if($memory['istate']){
			$ilast = " and `state`='\${istate}' ";
			array_push($escparam, 'istate');
			array_push($args, 'istate');
		}
		
		/**
		 *	@construct quthorize query
		**/
		$query = $memory['init'] ? "(select `chainid` from `members` where `chainid`=\${chainid} and `keyid`=\${keyid} and `control` like '%\${action}%' $last)" : 'false';
		
		$init = "(\${chainid})";
		$chain = "(select `chainid` from `members` where `chainid` in ";
		$chainend = " and `keyid`=\${keyid} and `control` like '%\${iaction}%' $ilast)";
		$child = "select `parent` from `webs` where `inherit` and `control` like '%\${iaction}%' $ilast and `child` in ";
		
		while($level--){
			$init = '('.$child.$init.')';
			$query = $query.' or '.$chain.$init.$chainend;
		}
		
		/*$join = '`chainid` in ';
		$master = "(select `chainid` from `chains` where `masterkey`=\${keyid})";
		$chain = "(select `chainid` from `members` where `keyid`=\${keyid})";
		$child = 'select `child` from `webs` where `parent` in ';
	
		$query = $memory['init'] ? ($join.$master.' or '.$join.$chain) : '';
		
		while($level--){
			$chain = '('.$child.$chain.')';
			$master = '('.$child.$master.')';
			$query = $query.' or '.$join.$master.' or '.$join.$chain;
		}*/
		
		/**
		 *	@execute authorize query
		**/
		$service = array(
			'service' => 'transpera.relation.unique.workflow',
			'args' => $args,
			'conn' => 'cbconn',
			'relation' => '`chains`',
			'sqlprj' => '`chainid`',
			'sqlcnd' => "where `chainid`=\${chainid} and (`masterkey`=\${keyid} or `authorize` not like '%\${action}%' or $query)",
			'escparam' => $escparam,
			'errormsg' => 'Unable to Authorize',
			'errstatus' => 403
		);
		
		$memory = Snowblozm::run($service, $memory);
		if($memory['admin'] && !$memory['valid']){
			$memory['admin'] = false;
			$memory['valid'] = true;
			$memory['msg'] = 'Successfully Executed';
			$memory['status'] = 200;
			$memory['details'] = 'Successfully executed';
		}
		
		return $memory;
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('admin', 'level', 'masterkey', 'authorize', 'state');
	}
	
}

?>