$(function() {
	loadEventSchedule();
});

function loadEventSchedule() {
	$.getJSON(baseUrl + "api/events.php", function(data) {
		$.each(data, function(key, val) {
			if($('#' + key).length) {
				eventSchedule(key, val.opentime, val.duration, val.offset, val.timeleft);
				document.getElementById(key + '_name').innerHTML = val.event;
				document.getElementById(key + '_next').innerHTML = val.nextF;
			}
		})
	})
}

function eventSchedule(eventId, openTime, duration, offset, timeLeft) {
	var eHours = null;
	var eMinutes = null;
	var eSeconds = null;
	
	function init() {
		setInterval(function() {
			update();
		}, 1000)
	}
	
	function reloadEventInfo() {
		$.getJSON(baseUrl + "api/events.php?event=" + eventId, function(data) {
			openTime = data.opentime;
			duration = data.duration;
			offset = data.offset;
			timeLeft = data.timeleft;
			document.getElementById(eventId + '_name').innerHTML = data.event;
			document.getElementById(eventId + '_next').innerHTML = data.nextF;
		})
	}
	
	function update() {
		if(timeLeft >= 1) {
			
			var days_module = timeLeft % 86400;
			eDays = (timeLeft-days_module)/86400;
			var hours_module = days_module % 3600;
			eHours = (days_module-hours_module)/3600;
			var minutes_module = hours_module % 60;
			eMinutes = (hours_module-minutes_module)/60;
			eSeconds = minutes_module;
			
			if(eMinutes < 10) eMinutes = '0' + eMinutes;
			if(eSeconds < 10) eSeconds = '0' + eSeconds;
		} else {
			eDays = '0';
			eHours = '0';
			eMinutes = '00';
			eSeconds = '00';
			
			reloadEventInfo();
		}
		
		if(openTime > 0) {
			if(offset-timeLeft < openTime) {
				document.getElementById(eventId).innerHTML = '<span class="event-schedule-open">Open</span>';
				timeLeft = timeLeft-1;
				return;
			}
		} else {
			if(duration > 0) {
				if(offset-timeLeft < duration) {
					document.getElementById(eventId).innerHTML = '<span class="event-schedule-inprogress">In Progress</span>';
					timeLeft = timeLeft-1;
					return;
				}
			}
		}
		
		if(eHours == '00' && eMinutes == '00') {
			document.getElementById(eventId).innerHTML = eSeconds + " sec";
		} else {
			if(eDays > 0) {
				document.getElementById(eventId).innerHTML = eDays + " days " + eHours + " hrs " + eMinutes + " min " + eSeconds + " sec";
			} else {
				document.getElementById(eventId).innerHTML = eHours + " hrs " + eMinutes + " min " + eSeconds + " sec";
			}
		}
		
		timeLeft = timeLeft-1;
	}
	
	init();
};