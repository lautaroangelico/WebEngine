document.addEventListener('DOMContentLoaded', function() {
	// Initiate Server Time
	serverTime.init("tServerTime", "tLocalTime", "tServerDate", "tLocalDate");
	
	// Initiate Castle Siege Countdown
	csTime.init();
	
	// Initiate bootstrap tooltips
	const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
	if(tooltips.length){
		tooltips.forEach(tooltip => {
			new bootstrap.Tooltip(tooltip);
		});
	}
	
	// PayPal Buy Credits
	const paypalConversionRate = document.getElementById('paypal_conversion_rate_value');
	if(paypalConversionRate) {
		const paypal_cr = parseInt(paypalConversionRate.textContent);
		const amountInput = document.getElementById('amount');
		if(amountInput) {
			amountInput.addEventListener('keyup', function(ev) {
				let num = 0;
				let c = 0;
				const result = document.getElementById('result');
				
				for(num = 0; num < this.value.length; num++) {
					c = this.value.charCodeAt(num);
					if(c < 48 || c > 57) {
						result.textContent = '0';
						this.value = '';
						return false;
					}
				}
				
				num = parseInt(this.value);
				if(isNaN(num)) {
					result.textContent = '0';
				} else {
					result.textContent = (paypal_cr * num).toString();
				}
			});
		}
	}
	
	// Rankings icon update
	const filterSelections = document.querySelectorAll(".rankings-class-filter-selection");
	
	if(filterSelections.length) {
		filterSelections.forEach(selection => {
			selection.addEventListener('click', function() {
				filterSelections.forEach(s => s.classList.add("rankings-class-filter-grayscale"));
				this.classList.remove("rankings-class-filter-grayscale");
			});
		});
	}
	
	// Language selector for mobile
	const languageSelector = document.querySelector('.webengine-language-switcher a');
	let autoClose;
	
	languageSelector.addEventListener('touchstart', () => {
		languageSelector.closest('.webengine-language-switcher').style.cssText = "width: 250px;overflow: visible;";
		
		clearTimeout(autoClose);
		
		autoClose = setTimeout(() => {
            languageSelector.closest('.webengine-language-switcher').style.cssText = "width: 46px;overflow: hidden;";
        }, 5000);
    });

});

var csTime = {
	csDays: null,
	csHours: null,
	csMinutes: null,
	csSeconds: null,
	csTimeLeft: null,
	csNextStageTimeLeft: null,
	battleMode: false,
	days_module: null,
	hours_module: null,
	minutes_module: null,
	cscountdown: null,
	siegeTimer: null,
	init: function() {
		this.cscountdown = document.getElementById("cscountdown");
		this.siegeTimer = document.getElementById("siegeTimer");
		
		if(this.siegeTimer || this.cscountdown) { //request api only if required html element found
			const a = this;
			
			fetch(`${baseUrl}api/castlesiege.php`).then(response => response.json()).then(c => {
				a.csTimeLeft = c.TimeLeft;
				a.csNextStageTimeLeft = c.NextStageTimeLeft;
				setInterval(function() {
					a.update();
				}, 1000);
				a.update();
			});
		}
	},
	update: function() {
		const b = this;
		
		b.csTimeLeft = b.csTimeLeft-1;
		b.csNextStageTimeLeft = b.csNextStageTimeLeft-1;
		
		if(b.csTimeLeft >= 1) {
			b.days_module = b.csTimeLeft % 86400;
			b.csDays = (b.csTimeLeft-b.days_module)/86400;
			b.hours_module = b.days_module % 3600;
			b.csHours = (b.days_module-b.hours_module)/3600;
			b.minutes_module = b.hours_module % 60;
			b.csMinutes = (b.hours_module-b.minutes_module)/60;
			b.csSeconds = b.minutes_module;
		} else {
			b.battleMode = true;
			b.csDays = 0;
			b.csHours = 0;
			b.csMinutes = 0;
			b.csSeconds = 0;
		}
		
		if(b.battleMode == true) {
			if(b.cscountdown) {
				b.cscountdown.textContent = 'Battle';
			}
			if(b.siegeTimer) {
				b.siegeTimer.textContent = 'Battle';
			}
		} else {
			var countdown = '';
			if(b.csTimeLeft > 86400) countdown += b.csDays + "<span>d</span> ";
			if(b.csTimeLeft > 3600) countdown += b.csHours + "<span>h</span> ";
			if(b.csTimeLeft > 60) countdown += b.csMinutes + "<span>m</span> ";
			countdown += b.csSeconds + "<span>s</span>";
			
			if(b.cscountdown) {
				b.cscountdown.innerHTML = countdown;
			}
			if(b.siegeTimer) {
				b.siegeTimer.innerHTML = countdown;
			}
		}
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
	eleServerDate: null,
	eleLocalDate: null,
	init: function(e, c, s, l) {
		const f = this;
		f.eleServer = e;
		f.eleLocal = c;
		f.eleServerDate = s;
		f.eleLocalDate = l;
		f.serverDate = new Date(serverPHPTime);
		f.localDate = new Date();
		f.dateOffset = f.serverDate - f.localDate;
		document.getElementById(f.eleServer).textContent = f.dateTimeFormat(f.serverDate);
		document.getElementById(f.eleLocal).textContent = f.dateTimeFormat(f.localDate);
		document.getElementById(f.eleServerDate).textContent = f.dateFormat(f.serverDate);
		document.getElementById(f.eleLocalDate).textContent = f.dateFormat(f.localDate);
		
		setInterval(function() {
			f.update();
		}, 1000);
	},
	update: function() {
		var b = this;
		b.nowDate = new Date();
		document.getElementById(b.eleLocal).textContent = b.dateTimeFormat(b.nowDate);
		b.nowDate.setTime(b.nowDate.getTime() + b.dateOffset);
		document.getElementById(b.eleServer).textContent = b.dateTimeFormat(b.nowDate);
	},
	dateTimeFormat: function(e) {
		var c = this;
		var f = [];
		f.push(c.digit(e.getHours()));
		f.push(":");
		f.push(c.digit(e.getMinutes()));
		f.push(":");
		f.push(c.digit(e.getSeconds()));
		return f.join("")
	},
	dateFormat: function(e) {
		var c = this;
		var f = [];
		f.push(c.weekDays[e.getDay()]);
		f.push(" ");
		f.push(c.monthNames[e.getMonth()]);
		f.push(" ");
        f.push(e.getDate());
		return f.join("")
	},
	digit: function(b) {
		b = String(b);
		b = b.length == 1 ? "0" + b : b;
		return b
	}
};

function rankingsFilterByClass() {
  const delay = 500;  // milliseconds
  const classList = Array.from(arguments);

  const rankingsTable = document.querySelector(".rankings-table");
  if (rankingsTable) {
    fadeOut(rankingsTable, delay);

    setTimeout(() => {
      const rows = rankingsTable.querySelectorAll("tr");
      rows.forEach(row => {
        const classId = row.getAttribute("data-class-id");
        if (classId === null) return;
        if (!classList.includes(parseInt(classId))) {
          row.style.display = 'none';
        } else {
          row.style.display = '';
        }
      });

      fadeIn(rankingsTable, delay);
    }, delay);
  }
}

function rankingsFilterRemove() {
	const delay = 500; // milliseconds
	const rankingsTable = document.querySelector(".rankings-table");
	
	if(rankingsTable) {
		fadeOut(rankingsTable, delay);

		setTimeout(() => {
			const rows = rankingsTable.querySelectorAll("tr");
			rows.forEach(row => {
				row.style.display = '';
			});

		  fadeIn(rankingsTable, delay);
		}, delay);
	}
}

function fadeOut(element, duration) {
  let startTime = null;
  const initialOpacity = 1;

  function updateOpacity(time) {
    if (!startTime) startTime = time;
    const progress = (time - startTime) / duration;
    const opacity = initialOpacity - progress;

    element.style.opacity = Math.max(opacity, 0);

    if (progress < 1) {
      requestAnimationFrame(updateOpacity);
    } else {
      element.style.opacity = 0;
    }
  }

  requestAnimationFrame(updateOpacity);
}

function fadeIn(element, duration) {
  let startTime = null;
  const initialOpacity = 0;

  function updateOpacity(time) {
    if (!startTime) startTime = time;
    const progress = (time - startTime) / duration;
    const opacity = initialOpacity + progress;

    element.style.opacity = Math.min(opacity, 1);

    if (progress < 1) {
      requestAnimationFrame(updateOpacity);
    } else {
      element.style.opacity = 1;
    }
  }

  requestAnimationFrame(updateOpacity);
}