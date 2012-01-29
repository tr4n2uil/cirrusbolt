<?php 
require_once(SBSERVICE);
require_once(SBMYSQL);

/**
 *	@class CurlExecuteService
 *	@desc Executes cURL request and returns response
 *
 *	@param url string URL [memory]
 *	@param data array/string Data to send with request [memory]
 *	@param plain boolean [memory] optional default false
 *
 *	@return response string Response [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *	
**/
class CurlExecuteService implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('url', 'data'),
			'optional' => array('plain' => false)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$url = $memory['url'];
		$data = $memory['data'];
		$plain = $memory['plain'];
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		
		if($plain)
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/plain')); 
			
		$result = curl_exec ($ch);
		$info = curl_getinfo($ch);
		curl_close($ch);
		
		if ($result === false || $info['http_code'] != 200){
			$memory['valid'] = false;
			$memory['msg'] = 'Error in cURL';
			$memory['status'] = $info['http_code'];
			$memory['details'] = 'Curl error : '.curl_error($ch).' @curl.execute.service';
			return $memory;
		}

		$memory['response'] = $result;
		$memory['valid'] = true;
		$memory['msg'] = 'Valid cURL Execution';
		$memory['status'] = 200;
		$memory['details'] = 'Successfully executed';
		return $memory;
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('response');
	}
	
}

?>