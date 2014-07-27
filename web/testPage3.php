<html><head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>InfoScreen</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/darkstrap.css" rel="stylesheet">
    <link href="css/extras.css" rel="stylesheet">
</head><body>
<div class="well" style="width: 250px; float: left; margin: 20px;">
    <legend>Solar Power</legend>
    <h2><span id="solarPower">power</span>
    <small>Yesterday: <span id="solarYesterday">yesterday</span></small></h2>
</div>

<div class="well" style="float: left; margin: 20px;">
    <legend>Grid Power</legend>
    <h2><span id="realPower">power</span>
    <small>Today: <span id="realToday">time</span>
    Yesterday: <span id="realYesterday">time</span></small></h2>
</div>

<div class="well" style="float: left; margin: 20px">
    <legend>Time</legend>
    <h2><span id="time">time</span><br />
    <small><span id="date">date</span></small></h2>
</div>
<div class="well" style="float: left; margin: 20px">
    <legend>Weather</legend>
    <div id="weather"></div>
</div>


<p style="clear: both">
    <button type="button" class="btn btn-default" onclick="updatePage()">Force Update</button>
</p>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    <script src="eduardoJS.js" type="text/javascript"></script>
    <script src="js/jquery.simpleWeather.min.js" type="text/javascript"></script>
    <script>startPage(); updatePage();</script>
</body></html>