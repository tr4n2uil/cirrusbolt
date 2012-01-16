<?php 
require_once(SBSERVICE);

/**
 *	@class ChainAuthorizeWorkflow
 *	@desc Authorizes key for chain operations and returns admin flag for action
 *
 *	@param chainid long int Chain ID [memory]
 *	@param keyid long int Key ID [memory]
 *	@param level integer Web level [memory] optional default 0
 *	@param action string Action to authorize [memory] optional default 'edit'
 *	@param type string Type name [memory] optional default 'general'
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
			'optional' => array('level' => 0, 'action' => 'edit', 'type' => 'general', 'admin' => false, 'init' => true)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$sqlprj = $memory['admin'] ? 'count(`chainid`) as `admin`' : '`chainid`';
		
		$next = $level = $memory['level'];
		
		$init = "(\${chainid})";
		$master = "(select `masterkey` from `chains` where `chainid` in ";
		$masterend = " and `masterkey`=\${keyid})";
		$chain = "(select `keyid` from `members` where `state` <> '0' and `type`='\${type}' and `control` like '%\${action}%' and `chainid` in ";
		$chainend = " and `keyid`=\${keyid})";
		$child = "select `parent` from `webs` where `state` <> '0' and `type`='\${type}' and `control` like '%\${action}%' and `child` in ";
		
		$query = $memory['init'] ? ($master.$init.$masterend.' or '.$chain.$init.$chainend) : 'false';
		
		while($level--){
			$init = '('.$child.$init.')';
			$query = $query.' or '.$master.$init.$masterend.' or '.$chain.$init.$chainend;
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
		
		$workflow = array(
		array(
			'service' => 'transpera.relation.unique.workflow',
			'args' => array('keyid', 'chainid', 'action', 'type'),
			'conn' => 'cbconn',
			'relation' => '`chains`',
			'sqlprj' => $sqlprj,
			'sqlcnd' => "where `chainid`=\${chainid} and (`authorize` not like '%\${action}%' or $query)",
			'escparam' => array('action', 'type'),
			'errormsg' => 'Unable to Authorize',
			'errstatus' => 403
		));
		
		if($memory['admin']){
			array_push($workflow, array(
				'service' => 'cbcore.data.select.service',
				'args' => array('result'),
				'params' => array('result.0.admin' => 'admin')
			));
		}
		
		return Snowblozm::execute($workflow, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('admin', 'level');
	}
	
}

?>