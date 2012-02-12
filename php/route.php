<?php 

	/**
	 * 	@init Router
	 *
	 *	@config $DEFAULT_PAGE default page to route to
	 *	@config $FIRST_PARAM key of first param
	 *	@config $FWD default forward page
	 *
	**/
	$uri = count($_GET) ? array_keys($_GET) : array('/'.$DEFAULT_PAGE);
	$args = explode('!', $uri[0]);
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
		for($i=0; $i<$len; $i+=2){
			$map[$params[$i]] = str_replace('_', '.', $params[$i+1]);
		}
	}
	
	/**
	 * 	@save Router
	**/
	if(count($_POST))
		$_POST = array_merge($_POST, $map);
	else
		$_GET = array_merge($_GET, $map);

?>