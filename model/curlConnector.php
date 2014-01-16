<?php

class cURLConnector{
	
	public static function download_page($url, $fields, $requesttype = 'get', $responsetype = 'xml') {
	
		$ch = curl_init();
		
		$url .= '?';
		foreach ($fields as $key => $value) {
			$url .= "$key=$value&";
		}
		//Remove last element of the string (that is an ampersand).
		$url = substr_replace($url, "", -1);
		
		$chopt = array(
			CURLOPT_POST => FALSE,
			CURLOPT_HTTPGET => TRUE,
			CURLOPT_URL => $url,
			CURLOPT_HEADER => false,
			CURLOPT_RETURNTRANSFER => true,
		);
		
		curl_setopt_array($ch, $chopt);
		
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}
}
