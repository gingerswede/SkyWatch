<?php

require_once 'curlConnector.php';

class YahooGeocode{
	private static $m_appID = '33cyMM7g';
	private static $m_URL = 'http://where.yahooapis.com/geocode';
	private static $m_Country = ',+Sweden';
	private static $m_Locale = 'sv_SV';
	private static $m_Needle = array('å', 'ä', 'ö');
	private static $m_Replace = array('a', 'a', 'o');
	
	private static $m_nameQ = 'q';
	private static $m_nameLocale = 'locale';
	private static $m_nameAppId = 'appid';
	
	private $fields;
	
	public function __construct($areaname) {
		$areaname = strtolower($areaname);
		
		$areaname = str_replace(self::$m_Needle, self::$m_Replace, $areaname);
		
		$this->fields = array(
			self::$m_nameAppId => self::$m_appID,
			self::$m_nameLocale => self::$m_Locale,
			self::$m_nameQ => $areaname.self::$m_Country
		);
	}
	
	public function GetLatLong() {
		$xmlstring = cURLConnector::download_page(self::$m_URL, $this->fields);
		$result = array();
		$xml = simplexml_load_string($xmlstring);
		if ($xml->Error == 0) {
			foreach($xml as $key0 => $value0){
				if (strtolower($key0) == 'result') {
					$city = new City();
					//Why on earth does this work 0_o
					if ($value0->state === (string)null) {
						$city->county = utf8_decode((string)$value0->state);
					}
					else {
						$city->county = utf8_decode((string)$value0->county);
					}
					
					$city->name = utf8_decode((string)$value0->city);
					$city->lat = utf8_decode((string)$value0->latitude);
					$city->lng = utf8_decode((string)$value0->longitude);
					
					$result[] = $city;
				}
			}
		}
		
		$xmlstring = NULL;
		
		return $result;
	}
}
