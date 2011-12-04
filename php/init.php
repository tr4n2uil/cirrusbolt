<?php 

	/**
	 * 	@root CirrusBolt Database Interface
	**/
	define('CBROOT', dirname(__FILE__).'/' );

	/** 
	 *	@constants CirrusBolt
	**/
	define('CBMYSQL', CBROOT . 'lib/database/Mysql.class.php');
	
	/** 
	 *	@initialize SnowBlozm
	**/
	require_once(CBROOT. '../../../snowblozm/php/init.php');
	require_once(SBCORE);
	
	Snowblozm::add('cbcore', array(
		'root' => CBROOT.'core/',
		'location' => 'local'
	));
	
	Snowblozm::add('cypher', array(
		'root' => CBROOT.'cypher/',
		'location' => 'local'
	));
	
	Snowblozm::add('echo', array(
		'root' => CBROOT.'echo/',
		'location' => 'local'
	));
	
	Snowblozm::add('gauge', array(
		'root' => CBROOT.'gauge/',
		'location' => 'local'
	));
	
	Snowblozm::add('guard', array(
		'root' => CBROOT.'guard/',
		'location' => 'local'
	));
	
	Snowblozm::add('handle', array(
		'root' => CBROOT.'handle/',
		'location' => 'local'
	));
	
	Snowblozm::add('invoke', array(
		'root' => CBROOT.'invoke/',
		'location' => 'local'
	));
	
	Snowblozm::add('journal', array(
		'root' => CBROOT.'journal/',
		'location' => 'local'
	));
	
	Snowblozm::add('rdbms', array(
		'root' => CBROOT.'rdbms/',
		'location' => 'local'
	));
	
	Snowblozm::add('transpera', array(
		'root' => CBROOT.'transpera/',
		'location' => 'local'
	));
	
?>
