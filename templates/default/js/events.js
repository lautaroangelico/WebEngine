document.addEventListener("DOMContentLoaded", () => {
    loadEventSchedule();
});

async function loadEventSchedule(){
    try{
        const response = await fetch(`${baseUrl}api/events.php`);
        const data = await response.json();
        
        Object.entries(data).forEach(([key, val]) => {
            if(document.getElementById(key)){
                eventSchedule(key, val.opentime, val.duration, val.offset, val.timeleft);
                document.getElementById(`${key}_name`).textContent = val.event;
                document.getElementById(`${key}_next`).textContent = val.nextF;
            }
			else{
				const webEngineEventTimers = document.getElementById("WebEngineEventTimers");
				
				if(webEngineEventTimers){
					webEngineEventTimers.insertAdjacentHTML("beforeend", `
						<tr>
							<td><span id="${key}_name">${val.event}</span><br /><span class="smalltext">Starts In</span></td>
							<td class="text-end"><span id="${key}_next">${val.nextF}</span><br /><span class="smalltext" id="${key}"></span></td>
						</tr>
					`);
					eventSchedule(key, val.opentime, val.duration, val.offset, val.timeleft);
				}
			}
        });
    } catch (error){
        console.error("Error loading event schedule:", error);
    }
}

function eventSchedule(eventId, openTime, duration, offset, timeLeft){
    function update(){
        if(timeLeft >= 1){
            let days = Math.floor(timeLeft / 86400);
            let hours = Math.floor((timeLeft % 86400) / 3600);
            let minutes = Math.floor((timeLeft % 3600) / 60);
            let seconds = timeLeft % 60;

            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;

			if(openTime > 0 && offset - timeLeft < openTime){
                document.getElementById(eventId).innerHTML = '<span class="event-schedule-open">Open</span>';
            } else if(duration > 0 && offset - timeLeft < duration){
                document.getElementById(eventId).innerHTML = '<span class="event-schedule-inprogress">In Progress</span>';
            } else{
				let timeFormating = "";

				if(hours == 0 && minutes == "00"){
					timeFormating = `${seconds} sec`;
				} else if(days > 0){
					timeFormating = `${days} days ${hours} hrs ${minutes} min ${seconds} sec`;
				} else {
					timeFormating = `${hours} hrs ${minutes} min ${seconds} sec`;
				}
				
				document.getElementById(eventId).innerHTML = timeFormating;
			}

            timeLeft--;
        } else {
            reloadEventInfo();
        }
    }

    async function reloadEventInfo(){
        try{
            const response = await fetch(`${baseUrl}api/events.php?event=${eventId}`);
            const data = await response.json();

            openTime = data.opentime;
            duration = data.duration;
            offset = data.offset;
            timeLeft = data.timeleft;

            document.getElementById(`${eventId}_name`).textContent = data.event;
            document.getElementById(`${eventId}_next`).textContent = data.nextF;
        } catch (error){
            console.error("Error reloading event info:", error);
        }
    }

    setInterval(update, 1000);
    update();
}
