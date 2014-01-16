<?php
require_once('header/view.h');
require_once('header/model.h');

class CreateReportController {
	private $nav;
	private $formView;
	private $ftpConnection;
	
	public function __construct() {
		$this->nav = new NavigationView();
		$this->formView = new FormView();
		$this->geoNames = new GeoNames();
		$this->ftpConnection = new FTPConnector();
	}
	
	public function DoControll() {
		switch($this->nav->getAction()) {
			case NavigationView::GET_RESULT_VIA_CITY:
				$this->fetchResultViaCity();
				break;
			case NavigationView::GET_RESULT_VIA_LNG_LAT:
				$this->fetchResultViaLatLng($this->formView->getLat(), $this->formView->getLng());
				break;
			//case NavigationView::CLEAR_CACHE:
				//ClearCache::Clear();
				//break;
			default:
				$this->noAction();
				break;
		}
	}
	
	private function noAction() {
		$error = new PredefinedError();
		$error->Cause = 'No action passed to the server.';
		
		print json_encode($error);
		die();
	}
	
	private function fetchResultViaCity() {
		$city = $this->formView->getCityName();
		
		if (strtolower($city) == 'raccoon city' || strtolower($city) == 'racoon city') {
			$regionalReport = new RegionWeatherReport();
			$regionalReport->city = new RaccoonCity();
			$regionalReport->forcasts = array();
			
			for ($i = 0; $i < 5; $i++) {
				$regionalReport->forcasts[$i] = new RaccoonCityWeather($i);
			}
			
			print json_properencode::fixBadUnicodeForJson(json_encode($regionalReport));
			die();
		} else if (strtolower($city) == 'gotham city') {
			$regionalReport = new RegionWeatherReport();
			$regionalReport->city = new GothamCity();
			$regionalReport->forcasts = array();
			
			for ($i = 0; $i < 5; $i++) {
				$regionalReport->forcasts[$i] = new GothamCityWeather($i);
			}
			
			print json_properencode::fixBadUnicodeForJson(json_encode($regionalReport));
			die();
		} else if ($city) {
			$this->geoNames->getByName($city);
							
			if (count($this->geoNames->cities) > 1) {
				print json_properencode::fixBadUnicodeForJson(json_encode($this->geoNames->cities));
				die();
			}
			
			else {
				$this->fetchResultViaLatLng($this->geoNames->city->lat, $this->geoNames->city->lng);
			}
		}
		else {
			$error = new PredefinedError();
			$error->Cause = 'No city name specified';
			print json_properencode::fixBadUnicodeForJson(json_encode($error));
			die();
		}
	}
	
	private function fetchResultViaLatLng($lat, $lng) {
		
		if (($lat && $lng) && (is_numeric($lat) && is_numeric($lng))) {
			$correctCity = $this->geoNames->getByLatLng($lat, $lng);
			if (!is_string($correctCity->county) || $correctCity->county == (string)NULL) {
				$googleLocation = new GoogleLocation($lat, $lng);
				$correctCity->county = $googleLocation->county;
			}
			$prevForcast = $this->ftpConnection->getForcastTimeByCity($correctCity);
			
			if (!$prevForcast || (count($prevForcast) < 3) && (str_replace('.xml', '', $prevForcast) < time())) {
				$yr = new yrAPI($correctCity->lat, $correctCity->lng);
				
				$forcast = $yr->GetForecast();
				$forcasts = array();
				
				$forcasts[ForcastPage::AREA] = $correctCity;
				$forcasts[ForcastPage::FORCAST] = $forcast;
				
				$tmp = new ForcastPage($forcasts);
				$xml = $tmp->generateXML();
				
				XmlHandler::saveForcast($correctCity, $this->ftpConnection, $xml);
			}
			
			XmlHandler::getForcastByCity($correctCity, $this->ftpConnection);
			die();
		} else if (($lat && $lng) && ($lat == 'Raccoon' && $lng == 'City')) {
			$regionalReport = new RegionWeatherReport();
			$regionalReport->city = new RaccoonCity();
			$regionalReport->forcasts = array();
			
			for ($i = 0; $i < 5; $i++) {
				$regionalReport->forcasts[$i] = new RaccoonCityWeather($i);
			}
			
			print json_properencode::fixBadUnicodeForJson(json_encode($regionalReport));
			die();
		} else if (($lat && $lng) && (strtoupper($lat) == 'gotham' && strtolower($lng) == 'city')) {
			$regionalReport = new RegionWeatherReport();
			$regionalReport->city = new GothamCity();
			$regionalReport->forcasts = array();
			
			for ($i = 0; $i < 5; $i++) {
				$regionalReport->forcasts[$i] = new GothamCityWeather($i);
			}
			
			print json_properencode::fixBadUnicodeForJson(json_encode($regionalReport));
			die();
		}
		else {
			$error = new PredefinedError();
			$error->Cause = 'No lat or lng was supplied';
			print json_properencode::fixBadUnicodeForJson(json_encode($error));
		}
	}
}