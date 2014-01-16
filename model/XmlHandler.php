<?php
	class XmlHandler {
		private static $baseURL = 'http://skywatch.code-monkey.se/forcast/';
		
		public static function saveForcast(City $city, FTPConnector $ftpConnection, $xmlString) {				
			$xml = simplexml_load_string($xmlString);
			
			$timeStamp = strtotime($xml->forecast[0][ForcastPage::DATE]);
			$cityName = strtolower((string)$xml->area[ForcastPage::CITY_NAME]);
			$countyName = strtolower((string)$xml->area[ForcastPage::COUNTY_NAME]);
			$countryName = strtolower((string)$xml->area[ForcastPage::COUNTRY_NAME]);
			
			$ftpConnection->saveReport($city, $xmlString, $timeStamp);
		}
		
		public static function getForcastByCity(City $city, FTPConnector $ftpConnection, $xml = NULL) {
			$report = new RegionWeatherReport();
			$xml;
			$cityName = $city->name.'/';
			$countyName = ($city->county != null) ? $city->county.'/' : '';
			$countryName = ($city->country != null) ? $city->country.'/' : '';
			
			if ($xml == NULL) {
				$files = $ftpConnection->getForcastTimeByCity($city);
				if ($files) {
					$xml = simplexml_load_file(strtolower($ftpConnection->getCityDir($city).'/'.$files));
				}
			}
			else {
				$xml = simplexml_load_string($xml);
			}
				
					
			$report->city = new City();
	
			$report->city->country = (string)$xml->area[ForcastPage::COUNTRY_NAME];
			$report->city->county = (string)$xml->area[ForcastPage::COUNTY_NAME];
			$report->city->name = (string)$xml->area[ForcastPage::CITY_NAME];
			$report->city->lng = (float)$xml->area[ForcastPage::LONG];
			$report->city->lat = (float)$xml->area[ForcastPage::LAT];
			
			$report->forcasts = array();
			
			foreach($xml->forecast as $forcst) {					
				$forcast = new Forecast();
				$forcast->date = (string)$forcst[ForcastPage::DATE];
				$forcast->symbol = (string)$forcst[ForcastPage::ICON];
				$forcast->pressure = trim((string)$forcst->pressure);
				$forcast->temp = trim((string)$forcst->temperature);
				$forcast->winddirection = trim((string)$forcst->wind->winddirection);
				$forcast->windspeed = trim((string)$forcst->wind->windspeed);
				$forcast->percipitation = trim((string)$forcst->percipitation);
				$report->forcasts[] = $forcast;
			}

			echo json_properencode::fixBadUnicodeForJson(json_encode($report));
		}
		
		private static function urlExists($url) {
			$header = @get_headers($url);
			
			return preg_match('/404/', $header[0]) ? false : true;
		}
	}
