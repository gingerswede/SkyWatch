<?php
require_once('header/model.h');

class yrAPI {
	private static $m_URL = 'http://api.yr.no/weatherapi/locationforecast/1.8/';
	private static $m_nameLat = 'lat';
	private static $m_nameLng = 'lon';
	
	private static $m_nameTime = 'time';
	private static $m_nameTemp = 'temperature';
	private static $m_nameWindDirection = 'winddirection';
	private static $m_nameWindSpeed = 'windspeed';
	private static $m_namePreassure = 'pressure';
	private static $m_nameDay = 'day';
	
	const VALUE = 'value';
	const NAME = 'name';
	const MPS = 'mps';
	const NUMBER = 'number';
	const MAX_VALUE_PERCIPITATION = 'maxvalue';
	const PERCIPITATION = 'percipitation';
	const ICON = 'icon';
	
	private $m_fields = array();

	private static $m_iMax = 4;
	private static $m_midday = 12;
	
	public function __construct($lat, $lng) {
		$this->m_fields = array(
			self::$m_nameLat => $lat,
			self::$m_nameLng => $lng
		);
	}
	
	public function GetForecast() {
		$result = array();
		$i = 0;
		$j;
		
		$xmlstring = cURLConnector::download_page(self::$m_URL, $this->m_fields);
		
		$xml = simplexml_load_string($xmlstring);
		$symbol;
		
		foreach($xml as $key0 => $value0) {
			if ($key0 == "product") {
				foreach ($value0 as $key1 => $value1) {
					$i++;
					
					if(count($result) > self::$m_iMax)
						break;
					$to = (string)$value1['to'];
					$from = (string)$value1['from'];
					
					$prevTime;

					if(date('G', time()) > 12 && count($result) == 0 && $to == $from) {
						$symbol = $this->setSymbolAndPrecipitation($xml, $i);
						$result[] = $this->generateForecast($to, $value1->location, $symbol);
					}
					else if (preg_match('/T12:00:00/', $to)){
						if($to == $from) {
							$symbol = $this->setSymbolAndPrecipitation($xml, $i);
							$forecast = $this->generateForecast($to, $value1->location, $symbol);
							if (!$this->forecastInArray($forecast, $result)) {
								$result[] = $forecast;
							}
						}
					}
					
					
				}
			}
		}
		
		return $result;
	}

	private function forecastInArray($forecast, $result) {
		
		$forecastDay = date('j', strtotime($forecast->date));
		foreach($result as $value) {
			$arrayDay = date('j', strtotime($value->date));
			if ($forecastDay == $arrayDay) {
				return true;
			}
		}
		return false;
	}
	
	private function setSymbolAndPrecipitation($xml, $i) {
		$cont = true;
		$symbol = NULL;
		$percipitation = NULL;
		while($cont){
			$symbol = ($symbol == NULL) ? $xml->product->time[$i]->location->symbol : $symbol;
			$percipitation = ($percipitation == NULL) ? $xml->product->time[$i]->location->precipitation : $percipitation;
			
			if ($symbol != NULL && $percipitation != NULL) {
				$cont = false;
				$result[self::ICON] = $symbol;
				$result[self::PERCIPITATION] = $percipitation;
				return $result;
			}
		}
		
	}

	private function generateForecast($time, $xmlObject, $symbol) {
		$forcast = new Forecast();
				
		$forcast->date = $time;
		$forcast->pressure = (string)$xmlObject->pressure['value'];
		$forcast->temp = (string)$xmlObject->temperature['value'];
		$forcast->winddirection = (string)$xmlObject->windDirection['name'];
		$forcast->windspeed = (string)$xmlObject->windSpeed['mps'];
		$forcast->symbol = (string)$symbol[self::ICON][0]['number'];
		$forcast->percipitation = (string)$symbol[self::PERCIPITATION][0][self::MAX_VALUE_PERCIPITATION];
		
		return $forcast;
	}
}
