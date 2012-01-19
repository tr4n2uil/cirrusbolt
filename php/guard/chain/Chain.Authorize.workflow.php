<?php 
require_once(SBSERVICE);

/**
 *	@class ChainAuthorizeWorkflow
 *	@desc Authorizes key for chain operations and returns admin flag for action
 *
 *	@param chainid long int Chain ID [memory]
 *	@param keyid long int Key ID [memory]
 *	@param level integer Web level [memory] optional default 0
 *	@param action string Action to authorize member [memory] optional default 'edit'
 *	@param state string State to authorize member [memory] optional default false (true= Not '0')
 *	@param iaction string Action to authorize inherit [memory] optional default 'edit'
 *	@param istate string State to authorize inherit [memory] optional default false (true= Not '0')
 *	@param init boolean init flag [memory] optional default true
 *	@param admin boolean Is return admin flag [memory] optional default false
 *
 *	@return admin boolean Is admin [memory]
 *	@return level integer Web level [memory]
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
			'optional' => array('level' => 0, 'action' => 'edit', 'iaction' => 'edit', 'state' => false, 'istate' => false, 'admin' => false, 'init' => true)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$next = $level = $memory['level'];
		
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
		
		$memory['msg'] = 'Key authorized successfully';
		$memory['level'] = $next + 1;
		
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
		return array('admin', 'level');
	}
	
}

?>