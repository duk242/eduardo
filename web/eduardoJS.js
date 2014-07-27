// All the glorious JS code!
function startPage() {
    // Set intervals here! Try to offset to balance load out over time.
    var calcPowerTodayInterval = window.setInterval(function() { calcPowerToday(); }, 60000);
    var calcPowerYesterdayInterval = window.setInterval(function() { calcPowerYesterday(); }, 1800000);
    var calcPowerNowInterval = window.setInterval(function() { calcPowerNow(); }, 5100);
    var calcSolarNowInterval = window.setInterval(function() { calcSolarNow(); }, 5000);
    var calcTimeNowInterval = window.setInterval(function() { updateTime(); }, 900);
    var calcDateNowInterval = window.setInterval(function() { updateDate(); }, 6000);
    var updateWeatherInterval = window.setInterval(function() { updateWeather(); }, 180000);
}

function updatePage() {
    // Forces all the fields to update. Also called on page load.
    calcPowerToday();
    calcPowerYesterday();
    calcPowerNow();
    calcSolarNow();
    updateTime();
    updateDate();
    calcSolarYesterday();
    updateWeather();
}

// *************************
// POWER FUNCTIONS
// *************************

function calcPowerToday() {
    // Work out Date calculation
    var date = new Date();
    var realMonth = date.getMonth()+1;
    
    $.getJSON( "moduleConnector.php?n=modPower&f=powerCalcDay&a="+ date.getDate() + "/" + realMonth + "/" + date.getFullYear(), function(data) {
        $.each(data, function(key, val) { 
            if(key == "power") {
                roundedPower = Math.round(val*100)/100;
                powerToday= roundedPower + " kWh<br />";
                document.getElementById("realToday").innerHTML=powerToday;
            }
        });
    });
}

function calcPowerYesterday() {
    var date = new Date();
    var realMonth = date.getMonth()+1;
    // Get Yesterdays Date
    date.setDate(date.getDate() - 1);
    $.getJSON( "moduleConnector.php?n=modPower&f=powerCalcDay&a="+ date.getDate() + "/" + realMonth + "/" + date.getFullYear(), function(data) {
        $.each(data, function(key, val) { 
            if(key == "power") {
                
                roundedPower = Math.round(val*100)/100;
                powerYesterday= roundedPower + " kWh<br />";
                document.getElementById("realYesterday").innerHTML=powerYesterday;
            }
        });
    });
}

function calcPowerNow() {
    // Get Current Power
    $.getJSON( "moduleConnector.php?n=modPower&f=powerNow", function(data) {
        $.each(data, function(key, val) { 
            if(key == "power") {
                roundedPower = Math.round(val*100)/100;
                powerNow = roundedPower + " kW<br />";
                document.getElementById("realPower").innerHTML=powerNow;
            } else if (key == "timeSinceLast") {
                // If the time since last has been greater than 10sec ago, we're prob exporting power
                if(val > 10) {
                    // Set Power to <0.5 and time
                    txt="&lt;0.1 kW<br />";
                    document.getElementById("realPower").innerHTML=txt;
                }
                 
            }
        });
    });
}
// *************************
// SOLAR FUNCTIONS
// *************************
function calcSolarNow() {
    // ModSolar one
    $.getJSON("moduleConnector.php?n=modSolar&f=currentPower", function(data) {
        $.each(data, function(key, val) { 
            if(key=="power") {
                roundedPower = Math.round(val*100)/100;
                solarNow= roundedPower + " kW<br />";
                document.getElementById("solarPower").innerHTML=solarNow;
            } else if (key == "time") {
                // set time stuff
                var date = new Date();
                
                var checkDate = (date.getTime()/1000)-30; // 30sec ago Unix Epoch time.
                if(val < checkDate) {
                    document.getElementById("solarPower").innerHTML="Logger Down.";
                }
            }
        });
    });
}

function calcSolarYesterday() {
    // Calculate solar for yesterday.
    var date = new Date();
    date.setDate(date.getDate() - 1);
    $.getJSON("moduleConnector.php?n=modSolar&f=getDailyUsage&a="+ ("0" + date.getDate()).slice(-2) + "-" + ("0" + (date.getMonth()-1)).slice(-2) + "-" + date.getFullYear(), function(data) {
        $.each(data, function(key, val) {
            if(key=="generated") {
                document.getElementById("solarYesterday").innerHTML=val+" kW";
            }
        })
    })
}

// *************************
// CLOCK FUNCTIONS
// *************************

function updateTime() {
    // Update the Pretty clock
    var dateNow = new Date();
    var twelveHourTime;
    var amPM;
    if (dateNow.getHours() >= 12) {
        twelveHourTime = dateNow.getHours() - 12;
        amPM = "PM";
    } else {
        twelveHourTime = dateNow.getHours();
        amPM = "AM";
    };
    
    var niceTimeNow = String(twelveHourTime) + ":" + ("0" + dateNow.getMinutes()).slice(-2) + ":" + ("0" + dateNow.getSeconds()).slice(-2) + " " + amPM;
    document.getElementById("time").innerHTML=niceTimeNow;
}

function updateDate() {
    // Update the Date (separate function because it's more efficient)
    var dateNow = new Date();
    var niceDate = dateNow.toDateString();
    document.getElementById("date").innerHTML=niceDate;
}



function updateWeather() {
// Docs at http://simpleweatherjs.com
  $.simpleWeather({
    location: 'Coffs Harbour, Australia',
    woeid: '',
    unit: 'c',
    success: function(weather) {
      html = '<h2><i class="icon-'+weather.code+'" style="font-size: 100px;"></i> '+weather.temp+'&deg;'+weather.units.temp+'</h2>';
      html += "<small>";
      html += 'Low: '+weather.low+'&deg;'+weather.units.temp+'  High: '+weather.high+'&deg;'+weather.units.temp+'<br />';
      html += ''+weather.city+', '+weather.region+'<br />';
      //html += ''+weather.currently+'<br />';
      html += 'Tomorrow: '+weather.forecast[1].high+'&deg;'+weather.units.temp+' <i class="icon-'+weather.forecast[1].code+'"></i><br />';
      html += 'Next: '+weather.forecast[2].high+'&deg;'+weather.units.temp+' <i class="icon-'+weather.forecast[2].code+'"></i><br />';
      //html += ''+weather.wind.direction+' '+weather.wind.speed+' '+weather.units.speed+'<br /></small>';
      html += '</small>';
  
      $("#weather").html(html);
    },
    error: function(error) {
      $("#weather").html('<p>'+error+'</p>');
    }
  });
}





