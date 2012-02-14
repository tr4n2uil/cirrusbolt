<?php 

	/**
	 * 	@init Router
	 *
	 *	@config $DEFAULT_PAGE default page to route to
	 *	@config $FWD default forward page
	 *	@config $URI_FROM default 'path_info' ('path_info', 'get')
	 *	@config $PATH_PARAMS default array()
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
	
	$args = explode('~', $URI);
	$map = array();
	
	/**
	 * 	@route Path
	**/
	$path = explode('/', $args[0]);
	
	$max = count($path) - 4;
	for($i = 1; $i < $max; $i ++){
		$map[(isset($PATH_PARAMS[$i]) ? $PATH_PARAMS[$i] : $i)] = $path[$i];
	}
	
	$map['service'] = isset($path[$i]) && $path[$i] != '' ? $path[$i] : $DEFAULT_PAGE;
	if(isset($path[++$i])){
		if(is_numeric($path[$i])){
			$map['id'] = $path[$i];
			$map['name'] = isset($path[$i+1]) ? $path[$i+1] : '';
		}
		else
			$map['name'] = $path[$i];
	}
	
	//echo json_encode($map); exit;
	
	/**
	 * 	@route Params and Forward
	**/
	if(isset($args[1])){
		$params = explode('/', $args[1]);
		//$params[0] = $FIRST_PARAM;
		
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