<?php
	class ForcastPage {
		
		const AREA = 'area';
		const DATE = 'date';
		const FORCAST = 'forecast';
		const ICON = 'icon';
		const UNIT = 'unit';
		
		const CITY_NAME = 'city';
		const COUNTY_NAME = 'county';
		const COUNTRY_NAME = 'country';
		const LONG = 'lng';
		const LAT = 'lat';
		
		const TEMPERATURE = 'temperature';
		const PRESSURE = 'pressure';
		const WIND = 'wind';
		const WIND_SPEED = 'windspeed';
		const WIND_DIRECTION = 'winddirection';
		const PERCIPITATION = 'percipitation';
		
		private $m_data;
		public function __construct($indata) {	
			$this->m_data = $indata;
		}
		
		public function generateXML() {
			$xml = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n";	
			$xml .= "<!-- Weather info obtained from yr.no. Area information from geonames.org. -->\n";	
			
			$xml .= "<result>\n";
			$xml .= "\t<".self::AREA." ".self::CITY_NAME."=\"".$this->m_data[self::AREA]->name."\" ";
			$xml .= self::COUNTY_NAME."=\"".$this->m_data[self::AREA]->county."\" ";
			$xml .= self::COUNTRY_NAME."=\"".$this->m_data[self::AREA]->country."\" ";
			$xml .= self::LAT."=\"".$this->m_data[self::AREA]->lat."\" ".self::LONG."=\"".$this->m_data[self::AREA]->lng."\" />\n";
			
			foreach ($this->m_data[self::FORCAST] as $forcast) {
				
				//Wind start
				$wind = "\t\t<".self::WIND.">\n\t\t\t";
				$wind .= "<".self::WIND_SPEED." ".self::UNIT."=\"mps\">";
				$wind .= $forcast->windspeed;
				$wind .= "</".self::WIND_SPEED.">\n";
				
				$wind .= "\t\t\t<".self::WIND_DIRECTION.">";
				$wind .= $forcast->winddirection;
				$wind .= "</".self::WIND_DIRECTION.">\n";
				
				$wind .= "\t\t</".self::WIND.">\n";
				
				//Temp start
				$temp = "\t\t<".self::TEMPERATURE." ".self::UNIT."=\"celsius\">";
				$temp .= $forcast->temp;
				$temp .= "</".self::TEMPERATURE.">\n";
				
				//Pressure start
				$pres = "\t\t<".self::PRESSURE." ".self::UNIT."=\"hPa\">";
				$pres .= $forcast->pressure;
				$pres .= "</".self::PRESSURE.">\n";
				
				//Percipitation start
				$percipitation = "\t\t<".self::PERCIPITATION." ".self::UNIT."=\"mm\">";
				$percipitation .= $forcast->percipitation;
				$percipitation .="</".self::PERCIPITATION.">";
				
				
				$forcst = "\t<".self::FORCAST." ".self::DATE."=\"$forcast->date\" ".self::ICON."=\"$forcast->symbol\">\n";
				$forcst .= $temp;
				$forcst .= $pres;
				$forcst .= $percipitation;
				$forcst .= $wind;
				$forcst .= "\t</".self::FORCAST.">\n";
				
				$xml .= $forcst;
			}
			$xml .= "</result>";
			return $xml;
		}
	}
