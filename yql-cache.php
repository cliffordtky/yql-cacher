<?php
/*

	== YQL Cache (Clifford Tan) ==
	A simple class that provides a convenient way to cache temporary data on YQL in a key-value fashion.
	Note that this is not persistent storage and it is public. You should ot store sensitive data here.

	= Usage =
	There are only 3 methods : put, get and remove.
	1) PUT 		-> Simple add a key, a value and a timeout in seconds.
	2) GET 		-> Simply provide a key to retrieve the value
	3) REMOVE 	-> Simply provide a key to delete the value
	
	= Suitable for the following use cases =
	1) For apps that don't need databases but need to store information temporarily.
	2) For apps that require database but want to cache their content for better performance

	= Dependencies =
	1) cURL or file_get_contents
	2) YQL caching table (https://developer.yahoo.com/yql/console/#h=desc+yahoo.caching)

*/

namespace YQLService;

class cache{

	protected $randomnize_prefix = 'kjashfaj74yrn'; //Prefix this to randomnize the key. Set to something random that you want.
	protected $yql_query_base = 'https://query.yahooapis.com/v1/public/yql?format=json&diagnostics=false&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys&q=';

	public function __construct(){}

	private function request($url){
		if (function_exists('curl_init')){
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_URL,$url);
			$result=curl_exec($ch);
			curl_close($ch);			
		}else{
			$result = file_get_contents($url);
		}
		if ($result){
			return json_decode($result, true);	
		}else{
			return false;
		}	
	}

	public function put($key,$value,$duration){
		if (trim($key) !='' && trim($value) !='' && is_numeric($duration)){
			$query = $this->yql_query_base.urlencode("select * from yahoo.caching where cache_key='".$this->randomnize_prefix.$key."' and cache_value='".$value."' and timeout='".$duration."' and put='1'");
			if ($this->request($query)){
				return true;	
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

	public function get($key){
		if (trim($key) !=''){
			$query = $this->yql_query_base.urlencode("select * from yahoo.caching where cache_key='".$this->randomnize_prefix.$key."' and get='1'");
			$res = $this->request($query);
			if ($res){
				return $res['query']['results']['result'];
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

	public function remove($key){
		if (trim($key) !=''){
			$query = $this->yql_query_base.urlencode("select * from yahoo.caching where cache_key='".$this->randomnize_prefix.$key."' and remove='1'");
			$res = $this->request($query);
			if ($res){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

	public function __destruct(){}

}

$cache = new \YQLService\cache();
if ($cache->put('superbird','super bird value','3600')) echo $cache->get('superbird');

?>