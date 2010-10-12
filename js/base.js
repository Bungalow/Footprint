$(function() {
	//assign select handler to start point selector
	$('select.startPointSelector').change(function() {
		updateMileage($(this).parent().parent());
	});

	//assign click handler to last stop checkbox
	$('input.lastStopCheckbox').click(function() {
		updateMileage($(this).parent().parent());
	});

	//assign click handler to ignore check in checkbox
	$('input.ignoreCheckinCheckbox').click(function() {
		var tableRow = $(this).parent().parent();
		if ($(this).attr("checked")) {
			//disable selects, last stop, and set mileage to 0
			//set disabled items to their defaults
			tableRow.find('select.startPointSelector').val("-1").attr("disabled","disabled").end()
			.find('select.transportationModeSelector').val("0").attr("disabled","disabled").end()
			.find('input.lastStopCheckbox').attr("disabled","disabled").end()
			.find('td.mileageCell').html(0);
		}
		else {
			//not checked - activate inputs and calculate mileage
			tableRow.find(':disabled').removeAttr("disabled");
			updateMileage(tableRow);
		}
	});
	
	//update button click handler
	$('input.updateDestinationInfoButton').click(function() {
		//clear all progress indicator spans
		$('span.progressIndicator').html("");
		saveDestinationInfo($(this).parent().parent());
	});
	
	
	//assign select handler to transport mode selector -> updateMileageTotals()
		//should update dashboard info
		
		
		
});






function updateMileage(tableRow) {
	//use the table row to locate the necessary information to perform the calculations
	var startLat = startLong = endLat = endLong = 0;
	var mileageCell = tableRow.find('td.mileageCell');
	var startLoc = tableRow.find('select.startPointSelector').val();
	var lastStop = tableRow.find('input.lastStopCheckbox');
	
	switch (startLoc) {
		case "home":
			startLat = homebaseGeoLat;
			startLong = homebaseGeoLong;
			endLat = tableRow.find('input.geoLatCoord').val();
			endLong = tableRow.find('input.geoLongCoord').val();
			break;
			
		case "prev":
			//need to go to next row to get start coords
			startLat = tableRow.next().find('input.geoLatCoord').val();
			startLong = tableRow.next().find('input.geoLongCoord').val();
			endLat = tableRow.find('input.geoLatCoord').val();
			endLong = tableRow.find('input.geoLongCoord').val();
			break;
			
		case "-1":
			lastStop.removeAttr("checked");
			break;
			
		default:
			break;
	}
	var mileage = calcSphDistance(startLat, startLong, endLat, endLong);
	//check if 'laststop' is checked
	if (lastStop.attr("checked")) {
		//need to calculate distance from current checking to homebase
		mileage += calcSphDistance(endLat, endLong, homebaseGeoLat, homebaseGeoLong);
	}
	
	//replace mileage text
	mileageCell.html(mileage.toFixed(2));
}

//handle update button status -> disabled vs. enabled
// -> should be disabled until start point and transport mode are selected


function saveDestinationInfo(tableRow) {
	//get and prep data
	var foursquareCheckInID = tableRow.find('input.foursquareCheckInID').val();
	var startPoint = tableRow.find('select.startPointSelector').val();
	
	var mileage = tableRow.find('td.mileageCell').html();
	//calculate mileage here and pass all digits rather than mileage with two significant digits?

	var transportMode = tableRow.find('select.transportationModeSelector').val();
	var lastStop = tableRow.find('input.lastStopCheckbox').attr("checked") ? 1 : 0;
	var ignoreCheckIn = tableRow.find('input.ignoreCheckinCheckbox').attr("checked") ? 1 : 0;
	var destinationUpdated = "";
	$.ajax({
		url:'SaveDestinationInfo.php',
		type:"POST",
		data:{
			foursquareCheckInID:foursquareCheckInID, 
			startPoint:startPoint, mileage:mileage, 
			transportMode:transportMode, 
			lastStop:lastStop, 
			ignoreCheckIn:ignoreCheckIn
		},
		beforeSend:function(){
			//show 'in progress' icon, deactivate all form fields?
			tableRow.find('span.progressIndicator').html("<img src='img/ajax-loader.gif' />");
		},
		complete:function(){
			//remove 'in progress' icon
			//tableRow.find('span.progressIndicator').html("");
			
		},
		success:function(destinationUpdated){
			switch (destinationUpdated) {
				case "success":
					tableRow.find('span.progressIndicator').html("updated");
					//disable 'update' button for the row?
					updateMileageTotals();
					break;
				case "db update failure":
					tableRow.find('span.progressIndicator').html("");
					alert("There was a problem updating this stop. Please try again. If the problem persists, please contact an administrator.");
					break;
				default:
					break;
			}
		}
		
		//need an error condition?
	});
}

// -> ajax call to db: get mileage totals and update display (in summary section)
// -> more complete 
function updateMileageTotals() {
	var mileageArray = "";
	$.ajax({
		url:'FootprintDataUpdate.php',
		type:"POST",
		data:{
			queryType:"mileage"
		},
		beforeSend:function(){
			
		},
		complete:function(){
			//remove 'in progress' icon
			//tableRow.find('span.progressIndicator').html("");
			
		},
		success:function(mileageArray){
			//update header info with data
			mileageArray = $.evalJSON(mileageArray);
			$('#mileageDetails span#totalMileage').html(mileageArray["totalMileage"]);
			$('#mileageDetails span#selfPowMileage').html(mileageArray["selfPowMileage"]);
			$('#mileageDetails span#massTransMileage').html(mileageArray["massTransMileage"]);
			$('#mileageDetails span#carMileage').html(mileageArray["carMileage"]);
			
			//update accomplishment section
			
			
			
			/*
			switch (destinationUpdated) {
				case "success":
					tableRow.find('span.progressIndicator').html("updated");
					//disable 'update' button for the row?
					break;
				case "db update failure":
					tableRow.find('span.progressIndicator').html("");
					alert("There was a problem updating this stop. Please try again. If the problem persists, please contact an administrator.");
					break;
				default:
					break;
			}
			*/
		}
		
		//need an error condition?
	});
}



function calcSphDistance(startLat, startLong, endLat, endLong) {
	startLat = startLat*Math.PI/180;
	startLong = startLong*Math.PI/180;
	endLat = endLat*Math.PI/180;
	endLong = endLong*Math.PI/180;
	return Math.acos(Math.sin(startLat)*Math.sin(endLat) + Math.cos(startLat)*Math.cos(endLat)*Math.cos(endLong-startLong))*3958.756;
}