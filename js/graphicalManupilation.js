var graphicalManipulation = {
	
	hideAllDivsInContent: function() {
		$('#mainContent').find('div').hide();
	},
	
	displayCitySelection: function(cities) {
		this.hideAllDivsInContent();
	
		$('#citySelection').show();
		$('#citySelection').empty();
			
		$('#citySelection').prepend('<h2>Vilken stad menade du?</h2>');
		
		var newNode = $('<ul data-role="listview" data-inset="true" data-theme="a">');
		
		for (var i = 0; i < cities.length; i++) {
			$(newNode).append(this.appendCityToCitySelection(cities[i]));
		}
		$('#citySelection').append(newNode);
		$('#citySelection').trigger("create");
	},
	
	addForecast: function(forecast, collapsed) {
		var date = new Date(forecast.date);
		var day = ['Söndag', 'Måndag', 'Tisdag', 'Onsdag', 'Torsdag', 'Fredag', 'Lördag'];
		var newNode;
		
		if (!collapsed) {
			newNode = $('<div data-role="collapsible" data-collapsed="false" class="individualForecast">');
		} else {
			newNode = $('<div data-role="collapsible" class="individualForecast">');
		}
		
		$(newNode).append('<h3>'+day[date.getDay()]+' '+forecast.temp+' C</h3>');
		$(newNode).append('<p class="indForeCastSet" style="margin-top:0; padding-top: 0;">');
		
		$('.indForeCastSet', newNode).append('<img style="float: left;" src="pic/icons/60/'+forecast.symbol+'.png" alt="forecast icon">');
		
		if (forecast.percipitation == "") {
			$('.indForeCastSet', newNode).append('Nederbörd: Okänd<br />');		
		} else {
			$('.indForeCastSet', newNode).append('Nederbörd: '+forecast.percipitation+' mm<br />');
		}
		
		$('.indForeCastSet', newNode).append('Lufttryck: '+forecast.pressure+' hPa<br />');
		$('.indForeCastSet', newNode).append('Vind: '+forecast.windspeed+' m/s '+forecast.winddirection);
		return newNode;
	},
	
	appendCityToCitySelection: function(city) {
		return $('<li><a href="#" class="selectCityLink" id="'+city.lat+';'+city.lng+'">'+city.name+', '+city.county+' ('+city.country+')</a></li>');
	},
	
	appendPreviousSearch: function(city) {
		return $('<li><a href="#" class="prevSearchLink" id="'+city.lat+';'+city.lng+'">'+city.name+'</a></li>');
	},
	
	displayForecast: function(forecast) {
		this.hideAllDivsInContent();
		
		$('.activeForecastSet').empty();
		$('.activeForecastSet').show();
		
		$('.activeForecastSet').prepend('<h2>'+forecast.city.name+" - "+forecast.city.country+"</h2>");
		
		for (var i = 0; i < forecast.forcasts.length; i++) {
			if (i == 0) {
				$('.activeForecastSet').append(this.addForecast(forecast.forcasts[i], false));
			} else {
				$('.activeForecastSet').append(this.addForecast(forecast.forcasts[i], true));
			}
		}
		
		$('.activeForecastSet').trigger("create");
	},
	
	displayError: function(error) {
		this.hideAllDivsInContent();
		$('#errorMessages').empty();
		$('#errorMessages').show();
		
		var newNode = $('<div class="currentErrorMessage">');
		
		$(newNode).append('<h2>Ett fel har inträffat.</h2>');
		$(newNode).append('<p>Vi beklagar det inträffade. Försök igen om ett litet tag.</p>');
		
		if (error.hasOwnProperty('Cause')) {
			$(newNode).append('<p>Felmeddelande: '+ error.Cause + '</p>');
		}
		
		$('#errorMessages').append(newNode);
		
		$('#errorMessages').trigger("create");
	}
}