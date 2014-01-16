var ajaxFunctions = {
	getForecastViaCityName: function(cityName) {	
		$.mobile.loading('show', {theme:'d', text:'laddar',textVisible:true});
		$.ajaxSetup({
			url: 'adapter.php',
			type: 'GET'
		});
		
		$.ajax({
			data: {
				action: 1,
				city: cityName
			}}
		).success(
			function(response) {
				ajaxFunctions.useAjaxResponse($.parseJSON(response));
			}
		);
	},

	getForecastViaLatLng: function(lattitude, longitude) {
		$.mobile.loading('show', {theme:'d', text:'laddar',textVisible:true});
		$.ajaxSetup({
			url: 'adapter.php',
			type: 'GET'
		});
		
		$.ajax({
			data: {
				action: 2,
				lat: lattitude,
				lng: longitude
			}
		}).success(
			function(response) {
				ajaxFunctions.useAjaxResponse($.parseJSON(response));
			}
		);
	},


	getForecastViaGPS: function(position) {		
		var lat = position.coords.latitude;
		var lng = position.coords.longitude;
	
		ajaxFunctions.getForecastViaLatLng(lat, lng);
	},

	useAjaxResponse: function(response){		
		$.mobile.loading('hide');
		
		if ($.isArray(response)) {
			graphicalManipulation.displayCitySelection(response);
		} else {
			if (response.hasOwnProperty('city')) {
				graphicalManipulation.displayForecast(response);
				localStorageAccessors.saveCityToLocalStorage(response.city);
			} else {			
				graphicalManipulation.displayError(response);
			}
		}
	}
}