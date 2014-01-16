<?php
    class NavigationView{
    	const ACTION = 'action';
		const GET_FORM = 0;
		const GET_RESULT_VIA_CITY = 1;
		const GET_RESULT_VIA_LNG_LAT = 2;
		const CLEAR_CACHE = 3;
		
		public function getAction(){
			if(isset($_GET[self::ACTION]))
				return $_GET[self::ACTION];
			
			return self::GET_FORM;
		}
    }
