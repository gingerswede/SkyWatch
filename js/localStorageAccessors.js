var localStorageAccessors = {
	getCityNamesFromLocalStorage: function() {
		if (window.localStorage) {
			var storedForecasts = $.parseJSON(window.localStorage.getItem('se-code-monkey-skywatch-stored-cities'));
			
			if (storedForecasts) {
				return storedForecasts;
			} else {
				return [];
			}
		}
	},
	
	saveCityToLocalStorage: function(city) {
		if (window.localStorage) {
			var cities = this.getCityNamesFromLocalStorage();
			
			if ($.isArray(cities) && cities.length != 0 && !cities[0].hasOwnProperty.name) {
				cities = [];
			}
			
			if (!cities.cityIsInArray(city)) {
				var newCities = [];
				var maxLength = 10;
				
				if (cities == 0) {
					newCities[0] = city;
				} else if (cities.length < maxLength) {
					cities[cities.length] = city;
					newCities = cities;
				} else {				
					for (var i = 0; i < cities.length; i++) {
						newCities[i] = cities[i+1];
					}
					newCities[maxLength - 1] = city;
				}
				newCities = JSON.stringify(newCities);
				
				window.localStorage.setItem('se-code-monkey-skywatch-stored-cities', newCities);
			}
		}	
	}
}