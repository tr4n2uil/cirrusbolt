<?php 
require_once(SBSERVICE);

/**
 *	@class InterfaceTileService
 *	@desc Processes interface elements in Tile UI
 *
 *	@param cookiename string Cookie name [memory]
 *	@param cookieexpiry string Cookie expiry [memory]
 *	@param rootpath string Rootpath [memory]
 *	@param page string Tile UI content [memory] optional default 'home'
 *	@param pages object Array of static html pages [memory] optional default array()
  *	@param templates object Array of static tpl pages [memory] optional default array()
 *	@param reqtype string request type [memory] ('get', 'post', 'json', 'wddx', 'xml')
 *	@param restype string response types [memory] ('json', 'wddx', 'xml', 'plain', 'html'),
 *	@param crypt string Crypt types [memory] ('none', 'rc4', 'aes', 'blowfish', 'tripledes')
 *	@param hash string Hash types [memory] ('none', 'md5', 'sha1', 'crc32')
 *	@param access array allowed service provider names [memory] optional default false
 *	@param email string Identification email to be used if not set in message [memory] optional default false
 *	@param context string Application context for email [memory] optional default false
 *	@param html string Tile UI html [memory] optional default ''
 *	@param tiles string Tile UI tiles [memory] optional default ''
 *
 *	@return html string Tile UI html [memory]
 *	@return tiles string Tile UI tiles [memory]
 *	@return ui boolean UI flag [memory]
 *	@return execute boolean Service execute flag [memory]
 *	@return email string Email [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *	
**/
class InterfaceTileService implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('reqtype', 'restype', 'crypt' , 'hash'),
			'optional' => array('page' => 'home', 'pages' => array(), 'templates' => array(), 'access' => array(), 'email' => false, 'context' => false, 'html' => '', 'tiles' => '')
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['ui'] = true;
		$memory['execute'] = false;
		
		if(isset($memory['pages'][$memory['page']])){
			$page = $memory['pages'][$memory['page']];
		}
		else if(isset($memory['templates'][$memory['page']])){
			$memory['execute'] = true;
			$page = $memory['templates'][$memory['page']];
		}
		else {
			$page = $memory['pages']['error'];
		}
		
		if(is_array($page)){
			foreach($page as $pg){
				$memory['tiles'] .= file_get_contents($pg.'.tile.html');
				$memory['html'] .= file_get_contents($pg.'.html');
			}
		}
		else {
			$memory['tiles'] .= file_get_contents($page.'.tile.html');
			$memory['html'] .= file_get_contents($page.'.html');
		}
		
		/**
		 *	@invoke Launch Message if any 
		**/
		if($memory['execute']){
			$memory = Snowblozm::run(array(
				'service' => 'invoke.launch.message.workflow',
				'raw' => true
			), $memory);
			
			$memory['html'] .= '
				<script type="text/javascript">
					var TileUI = TileUI || {};
					TileUI.global = TileUI.global || {};
					TileUI.global.data = '.$memory['result'].';
				</script>';
		}
		
		$memory['valid'] = true;
		$memory['msg'] = 'Valid Tile UI Interface';
		$memory['status'] = 200;
		$memory['details'] = 'Successfully executed';
		return $memory;
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('html', 'tiles', 'ui', 'execute', 'email');
	}
	
}

?>