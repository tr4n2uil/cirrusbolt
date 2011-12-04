<?php 
require_once(SBSERVICE);

/**
 *	@class ChainCreateWorkflow
 *	@desc Creates new chain
 *
 *	@param masterkey long int Key ID [memory]
 *	@param authorize string Authorize control value [memory] optional default 'edit:child:list'
 *	@param root string Collation root [memory] optional default '/masterkey'
 *	@param level integer Web level [memory] optional default 0
 *
 *	@return return id long int Chain ID [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class ChainCreateWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('masterkey'),
			'optional' => array('level' => 0, 'root' => false, 'authorize' => 'edit:child:list')
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['msg'] = 'Chain created successfully';
		$memory['root'] = $memory['root'] ? $memory['root'] : '/'.$memory['masterkey'];
		
		$service = array(
			'service' => 'ad.relation.insert.workflow',
			'args' => array('masterkey', 'level', 'root', 'authorize'),
			'conn' => 'adconn',
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