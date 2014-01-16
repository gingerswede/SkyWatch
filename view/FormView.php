<?php

class FormView{
	//Constants
	const CITY_NAME = 'city';
	const LONGITUDE = 'lng';
	const LATTITUDE = 'lat';
	const COUNTY = 'county';
	
	//ID-names
	private static $townForm = 'townform';
	private static $lngLatForm = 'lnglatform';
	private static $townInput = 'towninput';
	private static $lngInput = 'lnginput';
	private static $latInput = 'latinput';
	
	//Class-names
	private static $input = 'input';
	private static $searcForm = 'searchform';
	private static $floatLeft = 'floatleft';
	private static $floatRight = 'floatright';
	
	//Method
	private static $post = 'post';
	private static $get = 'get';
	
	public function generateSearchForm() {
		$search = '<input type="submit" value="Search!" />';
		
		$townInput = '<div><div class="'.self::$floatLeft.'"><label class="'.self::$floatRight.'" 
						for="'.self::CITY_NAME.'">Sök via stadsnamn:</label></div>';
		$townInput .= '<div class="'.self::$floatRight.'"><input type="text" class="'.self::$floatLeft.' '.self::$input.'" 
						id="'.self::CITY_NAME.'" name="'.self::CITY_NAME.'" /></div></div>';
		
		$townForm = '<form action="" method="'.self::$get.'">';
		$townForm .= '<input type="hidden" name="'.NavigationView::ACTION.'" value="'.NavigationView::GET_RESULT_VIA_CITY.'" />';
		$townForm .= $townInput;
		$townForm .= $search;
		$townForm .= '</form>';
		
		$lngInput = '<div><div class="'.self::$floatLeft.'"><label class="'.self::$floatRight.'" 
						for="'.self::LONGITUDE.'">Longitud:</label></div>';
		$lngInput .= '<div class="'.self::$floatRight.'"><input type="text" class="'.self::$floatRight.' '.self::$input.'"
						id="'.self::LONGITUDE.'" name="'.self::LONGITUDE.'" /></div></div>';
						
		$latInput = '<div><div class="'.self::$floatLeft.'"><label class="'.self::$floatRight.'"
						for="'.self::LATTITUDE.'">Lattitud:</label></div>';
		$latInput .= '<div class="'.self::$floatRight.'"><input type="text" class="'.self::$floatRight.' '.self::$input.'"
						id="'.self::LATTITUDE.'" name="'.self::LATTITUDE.'" /></div></div>';
						
		$lngLatForm = '<form action="" method="'.self::$get.'">';
		$lngLatForm .= '<input type="hidden" name="'.NavigationView::ACTION.'" value="'.NavigationView::GET_RESULT_VIA_LNG_LAT.'" />';
		$lngLatForm .= $lngInput;
		$lngLatForm .= $latInput;
		$lngLatForm .= $search;
		$lngLatForm .= '</form>';
		
		$final = $townForm . $lngLatForm;
		
		return $final;
	}

	public function generateCitySelection($cities) {
		$result = "<div><ul>";
		foreach($cities as $city) {
			$result .= '<li><a href="?'.NavigationView::ACTION.'='.NavigationView::GET_RESULT_VIA_LNG_LAT.'&'.self::LATTITUDE.'='.$city->lat.'&';
			$result .= self::LONGITUDE.'='.$city->lng.'">'.$city->name.' - '.$city->county.' ('.$city->country.')</a></li>';
		}
		$result .= "</ul></div>";
		return $result;
	}

	public function getCityName() {
		return (array_key_exists(self::CITY_NAME, $_REQUEST)) ? $_REQUEST[self::CITY_NAME] : NULL;
	}
	
	public function getLat() {
		return (array_key_exists(self::LATTITUDE, $_REQUEST)) ? $_REQUEST[self::LATTITUDE] : NULL;
	}
	
	public function getLng() {
		return (array_key_exists(self::LONGITUDE, $_REQUEST)) ? $_REQUEST[self::LONGITUDE] : NULL;
	}
	
	public function getCounty() {
		return (array_key_exists(self::COUNTY, $_REQUEST)) ? $_REQUEST[self::COUNTY] : NULL;
	}
}