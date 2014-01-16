if(!Array.prototype.indexOf) {
    Array.prototype.indexOf = function(needle) {
        for(var i = 0; i < this.length; i++) {
            if(this[i] === needle) {
                return i;
            }
        }
        return -1;
    };
}

Array.prototype.isInArray = function(needle) {
	var indexOfValue = this.indexOf(value);
	
	if (indexOfValue > -1) {
		return true;
	} else {
		return false;
	}
}
	
Array.prototype.cityIsInArray = function(needle) {
	array = this;
	for (var i = 0; i < array.length; i++) {
		if (array[i].name === needle.name) {
			return true;
		}
	}
	
	return false;
}

$(
	function() {
		graphicalManipulation.hideAllDivsInContent();
		
		$('#splashScreen').show();
	}
)
