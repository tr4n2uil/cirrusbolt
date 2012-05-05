<?php 
require_once(SBSERVICE);

/**
 *	@class ChainAddWorkflow
 *	@desc Creates new chain
 *
 *	@param parent long int Parent ID [memory]
 *	@param masterkey long int Key ID [memory]
 *	@param authorize string Authorize control value [memory] optional default 'edit:add:remove:list'
 *	@param state string State value [memory] optional default 'A'
 *	@param user string Key ID [memory] optional default ''
 *	@param root string Collation root [memory] optional default '/masterkey'
 *	@param type string Type name [memory] optional default 'general'
 *	@param level integer Web level [memory] optional default 0
 *
 *	@return return id long int Chain ID [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class ChainAddWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('masterkey', 'parent'),
			'optional' => array('level' => 0, 'root' => false, 'user' => '', 'authorize' => 'edit:add:remove:list', 'state' => 'A', 'type' => 'general')
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['msg'] = 'Chain added successfully';
		$memory['root'] = $memory['root'] ? $memory['root'] : '/'.$memory['masterkey'];
		
		$service = array(
			'service' => 'transpera.relation.insert.workflow',
			'args' => array('parent', 'masterkey', 'level', 'root', 'user', 'authorize', 'state', 'type'),
			'conn' => 'cbconn',
			'relation' => '`chains`',
			'sqlcnd' => "(`parent`, `masterkey`, `authorize`, `state`, `level`, `root`, `user`, `author`, `type`, `ctime`, `rtime`, `wtime`) values (\${parent}, \${masterkey}, '\${authorize}', '\${state}', \${level}, '\${root}', '\${user}', '\${user}', '\${type}', now(), now(), now())",
			'escparam' => array('root', 'user', 'authorize', 'state', 'type')
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