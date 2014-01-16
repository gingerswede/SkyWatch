<?php
	class GeoNames {
		private static $m_username = 'username';
		private static $m_usernameData = 'ec222ba';
		private static $m_url = 'http://api.geonames.org/';
		private static $m_viaLatLng = 'findNearbyPostalCodes';
		private static $m_viaName = 'postalCodeSearch';
		private static $m_lat = 'lat';
		private static $m_lng = 'lng';
		private static $m_placeName = 'placename';
		
		public $city;
		private $cityName;
		public $cities;
				
		public function getByLatLng($lat, $lng) {
			
			$fieldarray = array(
				self::$m_username => self::$m_usernameData, 
				self::$m_lat => $lat,
				self::$m_lng => $lng,
				);
	
			return $this->populateCity(cURLConnector::download_page(self::$m_url.self::$m_viaLatLng, $fieldarray));
		}
		
		public function getByName($name) {
			$fieldarray = array(
				self::$m_username => self::$m_usernameData, 
				self::$m_placeName => $name
			);
			$this->cityName = $name;
			$this->populateCities(cURLConnector::download_page(self::$m_url.self::$m_viaName, $fieldarray));
		}
		
		private function populateCity($xmlString) {
			$xml = simplexml_load_string($xmlString);
						
			$this->city = new City();
			
			$this->city->county = (strtoupper(mb_detect_encoding((string)$xml->code[0]->adminName1)) != "UTF-8") ? utf8_decode((string)$xml->code[0]->adminName1) : (string)$xml->code[0]->adminName1;
			$this->city->country = (strtoupper(mb_detect_encoding((string)$xml->code[0]->countryCode)) != "UTF-8") ? utf8_decode((string)$xml->code[0]->countryCode) : (string)$xml->code[0]->countryCode;
			$this->city->name = (strtoupper(mb_detect_encoding((string)$xml->code[0]->name) != "UTF-8")) ? utf8_decode((string)$xml->code[0]->name) : (string)$xml->code[0]->name;
			$this->city->lat = (float)$xml->code[0]->lat;
			$this->city->lng = (float)$xml->code[0]->lng;
			
			return $this->city;
		}
		
		private function populateCities($xml) {
			$xml = simplexml_load_string($xml);
			
			$this->cities = array();
			$counties = array();
			$city;
			
			
			foreach ($xml->code as $cty) {
				if (!in_array((string)$cty->adminName1, $counties)) {
					
					$city = new City();
					$city->county = (strtoupper(mb_detect_encoding((string)$cty->adminName1)) != "UTF-8") ? utf8_decode((string)$cty->adminName1) : (string)$cty->adminName1;
					$city->country = (strtoupper(mb_detect_encoding((string)$cty->countryCode)) != "UTF-8") ? utf8_decode((string)$cty->countryCode) : (string)$cty->countryCode;
					$city->name = (strtoupper(mb_detect_encoding((string)$cty->name)) != "UTF-8") ? utf8_decode((string)$cty->name) : (string)$cty->name;
					$city->lat = (string)$cty->lat;
					$city->lng = (string)$cty->lng;
					$counties[] = (string)$cty->adminName1;
					$this->cities[] = $city;
				}
			}
			
			if (count($this->cities) == 1) {
				$this->city = $city;
			}
		}
	}
