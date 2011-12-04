<?php 
require_once(SBSERVICE);

/**
 *	@class DataDecodeService
 *	@desc Decodes JSON XML WDDX data into array (supports copy of GET and POST data)
 *
 *	@param type string Request type [memory] optional default 'json' ('get', 'post', 'json', 'xml', 'wddx')
 *	@param data string Data to be decoded [memory] optional default '' when type=('get', 'post')
 *
 *	@return result array Decoded data [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *	
**/
class DataDecodeService implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'optional' => array('type' => 'json', 'data' => '')
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$type = $memory['type'];
		$data = $memory['data'];
		
		switch($type){
			case 'get' :
				$result = $_GET;
				break;
			case 'post' :
				$result = $_POST;
				break;
			case 'json' :
				$result = json_decode($data, true);
				break;
			case 'xml' :
				$result = (array) @simplexml_load_string($data);
				break;
			case 'wddx' :
				$result = wddx_deserialize($data);
				break;
			default :
				$memory['valid'] = false;
				$memory['msg'] = 'Invalid Data Type';
				$memory['status'] = 501;
				$memory['details'] = 'Data decoding not supported for type : '.$type.' @data.decode.service';
				return $memory;
		}

		if($result === false || $result == null){
			$memory['result'] = array();
			$memory['valid'] = false;
			$memory['msg'] = 'Unable to decode data';
			$memory['status'] = 501;
			$memory['details'] = 'Data could not be decoded @data.decode.service';
			return $memory;
		}
		
		$memory['result'] = $result;
		$memory['valid'] = true;
		$memory['msg'] = 'Valid Data Decoding';
		$memory['status'] = 200;
		$memory['details'] = 'Successfully executed';
		return $memory;
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('result');
	}
	
}

?>