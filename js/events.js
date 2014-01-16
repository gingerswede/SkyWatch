$(document).on('click', '#aboutSkyWatchLink',
	function(e) {
		e.preventDefault();
		graphicalManipulation.hideAllDivsInContent();
		
		$('#aboutSkyWatch').show();
	}
);

$(document).on('click', '#previousSearchesLink',
	function(e) {
		e.preventDefault();
		
		graphicalManipulation.hideAllDivsInContent();
		$('#previousSearches').show();
		$('#previousSearches').empty();
		
		var storedPreviousSearches = localStorageAccessors.getCityNamesFromLocalStorage();
		$('#previousSearches').prepend('<h2>Tidigare sökta städer</h2>');
		var newNode = $('<ul data-role="listview" data-inset="true" data-theme="a">');
		
		for(var i = 0; i < storedPreviousSearches.length; i++) {
			$(newNode).append(graphicalManipulation.appendPreviousSearch(storedPreviousSearches[i]));
		}
		
		$('#previousSearches').append(newNode);		
		$('#previousSearches').trigger("create");
	}
);

$(document).on('click', '.prevSearchLink', 
	function(e) {
		e.preventDefault();		
		
		graphicalManipulation.hideAllDivsInContent();
		
		$('.activeForecastSet').empty();
		$('.activeForecastSet').show();
		
		var latlng = $(this).attr('id').split(';');
		
		ajaxFunctions.getForecastViaLatLng(latlng[0], latlng[1]);
	}
);

$(document).on('click', '.selectCityLink',
	function(e) {
		e.preventDefault();
		
		graphicalManipulation.hideAllDivsInContent();
		
		$('.activeForecastSet').empty();
		$('.activeForecastSet').show();
		
		var latlng = $(this).attr('id').split(';');
		
		ajaxFunctions.getForecastViaLatLng(latlng[0], latlng[1]);
	}
);

$(document).on('click', '#searchWeatherForArea',
	function(e) {
		e.preventDefault();
		
		$('#searchForm').popup("close");
		
		var cityName = $('#areaNameField').val();
		$('#areaNameField').val('');
		
		ajaxFunctions.getForecastViaCityName(cityName);
	}
);

$(document).on('click', '#getCoorsViaGPS',
	function(e) {
		e.preventDefault();
		
		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(ajaxFunctions.getForecastViaGPS);
		} else {
			graphicalManipulation.displayError({Error: true, Cause: "Your device does not support GPS co-ordinations."})
		}
	}
);
