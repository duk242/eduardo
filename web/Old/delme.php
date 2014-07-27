<html><head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Test Page</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/darkstrap.css" rel="stylesheet">


<script>
function prettyDate(passedDate) {
    // Takes a date in epoch form and prints in in dd/mm/yy hh:mm:ss form
    var date = new Date(passedDate * 1000);
    var day = date.getDate();
    var month = date.getMonth()+1;
    var year = date.getFullYear();
    var hours = date.getHours();
    var minutes = date.getMinutes();
    var seconds = date.getSeconds();
    minutes = minutes < 10 ? '0' + minutes : minutes;
    seconds = seconds < 10 ? '0' + seconds : seconds;
    var prettyDate = day + "/" + month + "/" + year + " " + hours + ":" + minutes + ":" + seconds;
    
    return prettyDate;
}

function loadXMLDoc()
{
var xmlhttp;
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }


xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
        xmlDoc=xmlhttp.responseXML;
        txt="";
        x=xmlDoc.getElementsByTagName("power");
        for (i=0;i<x.length;i++)
          {
          txt=txt + x[i].childNodes[0].nodeValue + " kWh<br>";
          }
        document.getElementById("power").innerHTML=txt;
        
        txt="";
        x=xmlDoc.getElementsByTagName("time");
        for (i=0;i<x.length;i++)
          {
          //var date = new Date(x[i].childNodes[0].nodeValue*1000);
          txt=txt + prettyDate(x[i].childNodes[0].nodeValue) + "<br>";
          }
        document.getElementById("time").innerHTML=txt;
        //document.getElementById("myDiv").innerHTML=xmlhttp.responseText;
    }
  } 


xmlhttp.open("GET","modSolar_xml.php",true);
xmlhttp.send();
}
</script>
<script>
window.setInterval(function() {
    loadXMLDoc();
}, 5000);
</script>


</head><body>
<div class="well" style="width: 200px;">
    <legend>Solar Power</legend>
    <h2><span id="power">power</span>
    <small><span id="time">time</span></small></h2>
    <button type="button" class="btn btn-default" onclick="loadXMLDoc()">Force Update</button>
</div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>

</body></html>