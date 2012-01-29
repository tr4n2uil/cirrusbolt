<?php 

	/**
	 * 	@root CirrusBolt Database Interface
	**/
	define('CBROOT', dirname(__FILE__).'/' );

	/** 
	 *	@constants CirrusBolt
	**/
	define('CBMYSQL', CBROOT . 'system/Mysql.class.php');
	
	/** 
	 *	@initialize SnowBlozm
	**/
	require_once(CBROOT. '../../snowblozm/php/init.php');
	require_once(SBCORE);
	
	Snowblozm::add('cbcore', array(
		'root' => CBROOT.'core/',
		'location' => 'local'
	));
	
	Snowblozm::add('cypher', array(
		'root' => CBROOT.'cypher/',
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
	
	Snowblozm::add('invoke', array(
		'root' => CBROOT.'invoke/',
		'location' => 'local'
	));
	
	Snowblozm::add('pool', array(
		'root' => CBROOT.'pool/',
		'location' => 'local'
	));
	
	Snowblozm::add('queue', array(
		'root' => CBROOT.'queue/',
		'location' => 'local'
	));
	
	Snowblozm::add('rdbms', array(
		'root' => CBROOT.'rdbms/',
		'location' => 'local'
	));
	
	Snowblozm::add('storage', array(
		'root' => CBROOT.'storage/',
		'location' => 'local'
	));
	
	Snowblozm::add('transpera', array(
		'root' => CBROOT.'transpera/',
		'location' => 'local'
	));
	
	/**
	 *	@dependencies
	**/
	//define('PHPMAILER', CBROOT.'../../../libraries/phpmailer/PHPMailer.class.php');
	define('CACHELITE', 'Cache/Lite.php');
	define('CACHELITEOUTPUT', 'Cache/Lite/Output.php');
	
?>
