<?php

class RaccoonCity
extends City {
	public function __construct() {
		$this->country = 'US';
		$this->county = 'Unknown';
		$this->name = 'Raccoon City';
		$this->lat = 'Raccoon';
		$this->lng = 'City';
		$this->comment = 'Be adviced, inhabitants are infected with the T-virus.';
	}
}

class RaccoonCityWeather
extends Forecast {
	
	private static $windDirection = array('N', 'NE', 'E', 'SE', 'S', 'SW', 'W', 'NW');
	
	public function __construct($daysToAdd) {
		$oneDay = 60*60*24;
		$daysToAdd = $daysToAdd * $oneDay;
		$this->date = date('Y-m-d', time()+$daysToAdd).'T'.date('G:i:s', time()+$daysToAdd).'Z';
		$this->percipitation = rand(1,9).'.'.rand(0,9);
		$this->pressure = rand(990, 1010);
		$this->temp = rand(1, 35);
		$this->winddirection = self::$windDirection[rand(0, count(self::$windDirection)-1)];
		$this->windspeed = rand(0, 10).'.'.rand(0,9);
		$this->comment = 'A forecast for high risk of T-virus infection.';
		$this->symbol = rand(9,11);
	}
}

class GothamCity
extends City {
	public function __construct() {
		$this->country = 'US';
		$this->county = 'New Jersey';
		$this->name = 'Gotham City';
		$this->lat = 'Gotham';
		$this->lng = 'City';
		$this->comment = 'Swear to me!';
	}
}

class GothamCityWeather
extends Forecast {
	
	private static $windDirection = array('N', 'NE', 'E', 'SE', 'S', 'SW', 'W', 'NW');
	
	public function __construct($daysToAdd) {
		$oneDay = 60*60*24;
		$daysToAdd = $daysToAdd * $oneDay;
		$this->date = date('Y-m-d', time()+$daysToAdd).'T'.date('G:i:s', time()+$daysToAdd).'Z';
		$this->percipitation = rand(1,9).'.'.rand(0,9);
		$this->pressure = rand(990, 1010);
		$this->temp = rand(1, 35);
		$this->winddirection = self::$windDirection[rand(0, count(self::$windDirection)-1)];
		$this->windspeed = rand(1, 10).'.'.rand(0,9);
		$this->comment = 'A forecast for high risk of whoop ass by the god damn batmat!';
		$this->symbol = 'batman';
	}
}