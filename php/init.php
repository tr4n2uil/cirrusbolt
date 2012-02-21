<?php 

	/**
	 * 	@root CirrusBolt
	**/
	define('CBROOT', dirname(__FILE__).'/' );

	/** 
	 *	@constants CirrusBolt
	**/
	define('CBMYSQL', CBROOT . 'aquadew/system/Mysql.class.php');
	
	/** 
	 *	@initialize SnowBlozm
	**/
	require_once(CBROOT. '../../snowblozm/php/init.php');
	require_once(SBCORE);
	
	Snowblozm::add('cbcore', array(
		'root' => CBROOT.'aquadew/core/',
		'location' => 'local'
	));
	
	Snowblozm::add('pool', array(
		'root' => CBROOT.'aquadew/pool/',
		'location' => 'local'
	));
	
	Snowblozm::add('rdbms', array(
		'root' => CBROOT.'aquadew/rdbms/',
		'location' => 'local'
	));
	
	Snowblozm::add('cypher', array(
		'root' => CBROOT.'swiftblaze/cypher/',
		'location' => 'local'
	));
	
	Snowblozm::add('gauge', array(
		'root' => CBROOT.'swiftblaze/gauge/',
		'location' => 'local'
	));
	
	Snowblozm::add('guard', array(
		'root' => CBROOT.'swiftblaze/guard/',
		'location' => 'local'
	));
	
	Snowblozm::add('invoke', array(
		'root' => CBROOT.'swiftblaze/invoke/',
		'location' => 'local'
	));
	
	Snowblozm::add('transpera', array(
		'root' => CBROOT.'swiftblaze/transpera/',
		'location' => 'local'
	));
	
	Snowblozm::add('display', array(
		'root' => CBROOT.'thundersky/display/',
		'location' => 'local'
	));
	
	Snowblozm::add('queue', array(
		'root' => CBROOT.'thundersky/queue/',
		'location' => 'local'
	));
	
	Snowblozm::add('storage', array(
		'root' => CBROOT.'thundersky/storage/',
		'location' => 'local'
	));
	
	Snowblozm::add('people', array(
		'root' => CBROOT.'rhythmlink/people/',
		'location' => 'local'
	));
	
	Snowblozm::add('office', array(
		'root' => CBROOT.'rhythmlink/office/',
		'location' => 'local'
	));
	
	/**
	 *	@dependencies
	**/
	//define('PHPMAILER', CBROOT.'../../../libraries/phpmailer/PHPMailer.class.php');
	//define('CBQUEUECONF', CBROOT. 'config.php');
	define('CACHELITE', 'Cache/Lite.php');
	define('CACHELITEOUTPUT', 'Cache/Lite/Output.php');
	
?>
