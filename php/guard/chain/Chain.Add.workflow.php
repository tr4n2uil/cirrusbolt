<?php 
require_once(SBSERVICE);

/**
 *	@class ChainAddWorkflow
 *	@desc Creates new chain
 *
 *	@param masterkey long int Key ID [memory]
 *	@param authorize string Authorize control value [memory] optional default 'edit:add:remove:list'
 *	@param root string Collation root [memory] optional default '/masterkey'
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
			'required' => array('masterkey'),
			'optional' => array('level' => 0, 'root' => false, 'authorize' => 'edit:add:remove:list')
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
			'args' => array('masterkey', 'level', 'root', 'authorize'),
			'conn' => 'cbconn',
			'relation' => '`chains`',
			'sqlcnd' => "(`masterkey`, `level`, `root`, `authorize`, `ctime`, `rtime`, `wtime`) values (\${masterkey}, \${level}, '\${root}', '\${authorize}', now(), now(), now())",
			'escparam' => array('root', 'authorize')
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