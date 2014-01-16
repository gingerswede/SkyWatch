<?php

require_once 'curlConnector.php';

class GoogleLocation {
	private static $m_url = 'http://maps.google.com/maps/api/geocode/xml';
	private static $m_sensor = 'sensor';
	private static $m_latlng = 'latlng';
	
	const CITY = 'city';
	const COUNTY = 'county';
	
	private $m_lat;
	private $m_lon;
	public $county;
	
	public function __construct($lat, $lon) {
		$this->m_lat = $lat;
		$this->m_lon = $lon;
		
		$fieldarray = array(
			self::$m_sensor => 'false', 
			self::$m_latlng => $this->m_lat .','. $this->m_lon);

		$this->details = $this->SetDetails(cURLConnector::download_page(self::$m_url, $fieldarray));
	}
	
	public function SetDetails($xmlstring) {
		$result = array();
		$xml = simplexml_load_string($xmlstring);
		foreach ($xml as $key0 => $value0) {
			//print_r('$value0 =>'. (string)$value0->type .'<br />');
			//if ((string)$value0->type == 'street_address') {				
				foreach ($value0 as $key1 => $value1) {
					if ((string)$value1->type == 'administrative_area_level_1') {
						$result = utf8_encode(preg_replace('/\s(?:län|county)/i', '', (string)$value1->long_name));
					}
				}
			//}
		}

		return trim($result);
	}
}
