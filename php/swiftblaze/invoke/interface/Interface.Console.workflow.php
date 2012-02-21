<?php 
require_once(SBSERVICE);

/**
 *	@class InterfaceConsoleWorkflow
 *	@desc Processes interface console requests in Tile UI
 *
 *	@param name string Tile UI content [memory] optional default 'home'
 *	@param pages object Array of static html pages [memory] optional default array()
 *
 *	@param html string Tile UI html [memory] optional default ''
 *	@param tiles string Tile UI tiles [memory] optional default ''
 *
 *	@return html string Tile UI html [memory]
 *	@return tiles string Tile UI tiles [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *	
**/
class InterfaceConsoleWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'optional' => array(
				'name' => 'home', 
				'pages' => array(), 
				'html' => '', 
				'tiles' => ''
			)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		if(isset($memory['pages'][$memory['name']])){
			$page = $memory['pages'][$memory['name']];
		}
		else {
			$page = $memory['pages']['error'];
		}
			
		if(is_array($page)){
			foreach($page as $pg){
				$memory['tiles'] .= @file_get_contents($pg.'.tile.html');
				$memory['html'] .= @file_get_contents($pg.'.html');
			}
		}
		else {
			$memory['tiles'] .= @file_get_contents($page.'.tile.html');
			$memory['html'] .= @file_get_contents($page.'.html');
		}

		$memory['valid'] = true;
		$memory['msg'] = 'Valid Tile UI Interface Console';
		$memory['status'] = 200;
		$memory['details'] = 'Successfully executed';
		return $memory;
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('html', 'tiles');
	}
	
}

?>