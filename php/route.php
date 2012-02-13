<?php 

	/**
	 * 	@init Router
	 *
	 *	@config $DEFAULT_PAGE default page to route to
	 *	@config $FIRST_PARAM key of first param (deprecated)
	 *	@config $FWD default forward page
	 *	@config $URI_FROM default 'path_info' ('path_info', 'get')
	 *
	**/
	switch($URI_FROM){
		case 'get' :
			$URI = count($_GET) ? array_keys($_GET) : array('/'.$DEFAULT_PAGE);
			$URI = $URI[0];
			break;
		case 'path_info' :
		default :
			$URI = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '/'.$DEFAULT_PAGE;
			break;
	}
	
	$args = explode('!', $URI);
	$map = array();
	
	/**
	 * 	@route Path
	**/
	$path = explode('/', $args[0]);
	$map['service'] = isset($path[1]) && $path[1] != '' ? $path[1] : $DEFAULT_PAGE;
	if(isset($path[2])){
		if(is_numeric($path[2])){
			$map['id'] = $path[2];
			$map['name'] = isset($path[3]) ? $path[3] : '';
		}
		else
			$map['name'] = $path[2];
	}
	
	/**
	 * 	@route Params and Forward
	**/
	if(isset($args[1])){
		$params = explode('/', $args[1]);
		$params[0] = $FIRST_PARAM;
		
		$len = count($params);
		if($len % 2 && $params[$len-1]){
			$len--;
			$FWD = str_replace('_', '.', $params[$len]);
		}
		
		$len--;
		for($i=1; $i<$len; $i+=2){
			$map[$params[$i]] = str_replace('_', '.', $params[$i+1]);
		}
	}
	else 
		$URI .= '~/';
	
	/**
	 * 	@save Router
	**/
	if(count($_POST))
		$_POST = array_merge($_POST, $map);
	else
		$_GET = array_merge($_GET, $map);

?>