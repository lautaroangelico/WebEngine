$(function() {
	// Initiate Server Time
	serverTime.init("tServerTime", "tLocalTime");
	
	// Initiate Castle Siege Countdown
	if($('#cscountdown').length) {
		csTime.init();
	}
	
	// Initiate bootstrap tooltips
	$('[data-toggle="tooltip"]').tooltip();
});

var csTime = {
	csHours: null,
	csMinutes: null,
	csSeconds: null,
	csTimeLeft: null,
	init: function() {
		var a = this;
		$.getJSON(baseUrl + "api/castlesiege.php", function(c) {
			a.csTimeLeft = c.TimeLeft;
			setInterval(function() {
				a.update()
			}, 1000)
		})
	},
	update: function() {
		var b = this;
		
		if(b.csTimeLeft >= 1) {
			var hours_module = b.csTimeLeft % 3600;
			b.csHours = (b.csTimeLeft-hours_module)/3600;
			var minutes_module = hours_module % 60;
			b.csMinutes = (hours_module-minutes_module)/60;
			b.csSeconds = minutes_module;
		} else {
			b.csHours = 0;
			b.csMinutes = 0;
			b.csSeconds = 0;
		}
		document.getElementById("cscountdown").innerHTML = b.csHours + "<span>h</span> " + b.csMinutes + "<span>m</span> " + b.csSeconds + "<span>s</span>";
		
		b.csTimeLeft = b.csTimeLeft-1;
	}
};

var serverTime = {
	weekDays: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
	monthNames: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
	serverDate: null,
	localDate: null,
	dateOffset: null,
	nowDate: null,
	eleServer: null,
	eleLocal: null,
	init: function(e, c) {
		var f = this;
		f.eleServer = e;
		f.eleLocal = c;
		$.getJSON(baseUrl + "api/servertime.php", function(a) {
			f.serverDate = new Date(a.ServerTime);
			f.localDate = new Date();
			f.dateOffset = f.serverDate - f.localDate;
			document.getElementById(f.eleServer).innerHTML = f.dateTimeFormat(f.serverDate);
			document.getElementById(f.eleLocal).innerHTML = f.dateTimeFormat(f.localDate);
			setInterval(function() {
				f.update()
			}, 1000)
		})
	},
	update: function() {
		var b = this;
		b.nowDate = new Date();
		document.getElementById(b.eleLocal).innerHTML = b.dateTimeFormat(b.nowDate);
		b.nowDate.setTime(b.nowDate.getTime() + b.dateOffset);
		document.getElementById(b.eleServer).innerHTML = b.dateTimeFormat(b.nowDate)
	},
	dateTimeFormat: function(e) {
		var c = this;
		var f = [];
		f.push(c.digit(e.getHours()));
		f.push(":");
		f.push(c.digit(e.getMinutes()));
		return f.join("")
	},
	digit: function(b) {
		b = String(b);
		b = b.length == 1 ? "0" + b : b;
		return b
	}
};