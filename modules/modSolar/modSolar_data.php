<?php
    // modSolar_data.php
    // Controls output/reading of data from the database.
    // Author: Dustin Kerr
    // Date Created: 23/2/14    Modified: 14/6/14
    
    class modSolar {
        private $db;
    
        function __construct() {
            // Connect to the Database
            global $db;
            $dbLocation = dirname(__FILE__)."/../../database.sqlite";
            $db = new SQLite3($dbLocation);
            $db->busyTimeout(10000);     // Stops silly locking issues. 10000=10s timeout. 
        }
        
        function currentPower() {
            // Get latest power update from DB and Time
            // RETURNS: Keyed Array eg: time=>1402732305  power=>1.5
            global $db;
            // Gets the latest result
            $query = "SELECT * FROM solar ORDER BY time DESC LIMIT 1";
            $result = $db->query($query);
            $latestSolar = $result->fetchArray();
            return(array("power"=>$latestSolar['power'], "time"=>$latestSolar['time'])); 
        }
        
        function generateTimeGraph($startTime, $endTime) {
            // Generates a graph (Not great yet)
            // Outputs to basic.png in the same folder as the script for now...
            // INPUTS: $startTime & $endTime in UNIX Epoch form. eg. 1402732305
            global $db;
            // Prevent Invalid Input
            if(!preg_match("/\d+/", $startTime)) { die("Invalid Input"); }
            if(!preg_match("/\d+/", $endTime)) { die("Invalid Input"); }
            
            // Initial Graph Stuff
            include(dirname(__FILE__)."/../../pChart/class/pData.class.php");
            include(dirname(__FILE__)."/../../pChart/class/pDraw.class.php");
            include(dirname(__FILE__)."/../../pChart/class/pImage.class.php");
            
            $myData = new pData();
            // Get all the values from the DB for the time specified
            $query = "SELECT * FROM solar WHERE time > $startTime AND time < $endTime";
            $result = $db->query($query);
            $i = 0;
            while($line = $result->fetchArray()) {
                // Loop through and add them to the graph
                $timeArray[$i] = $line['time'];
                $powerArray[$i] = $line['power'];
                $i++;
            }
            $myData->addPoints($powerArray, "Power");
            $myData->addPoints($timeArray, "Time");
            $myData->setAbscissa("Time");   
            $myData->setXAxisDisplay(AXIS_FORMAT_DATE, "H:i");
            $myPicture = new pImage(700,400,$myData);
            $myPicture->setGraphArea(60,40,670,380);
            $myPicture->setFontProperties(array("FontName"=>dirname(__FILE__)."/../../pChart/fonts/Forgotte.ttf","FontSize"=>11));
            
            // When drawing the X Axis, need to determine how many points are there and how many to skip
            $myPicture->drawScale(array("LabelSkip"=>5,"LabelRotation"=>90));
            $myPicture->drawSplineChart();
            $myPicture->Render("basic.png");
        }
        
        function getDailyUsage($date) {
            // Returns the Usage for any day *except* for the current day.
            // INPUTS: $date in form dd-mm-yyyy (exactly)
            // RETURNS: keyed Array: generated=>14.5 (kWh)

            // Prevent Invalid Input
            if(!preg_match("/^\d{2}-\d{2}-\d{4}$/", $date)) { die("Invalid Input"); }

            global $db;
            $query = "SELECT * FROM solarDaily WHERE solarDate=\"$date\"";
            $result = $db->query($query);
            $line = $result->fetchArray();
            return array("generated"=>$line['power']);


            // TODO: Compare against numbers from the solar unit itself
            // THIS IS WRONG AHHHHHH. USE GET SOLAR DATE INSTEAD - In practice comes out ~10-15% out from the solar unit.
            // $sleepTime = 10;    // Change this if you change it in the collector!
            // $totalGenerated = 0;
            
            // $query = "SELECT * FROM solar WHERE time > $startTime AND time < $endTime";
            // $result = $db->query($query);
            // while($line = $result->fetchArray()) {
            //     // Get 1/3600th of value to convert kW to kWh
            //     $totalGenerated = $totalGenerated + (($line['power']*10)*(1/3600));
            // }
            // return array("generated"=>$totalGenerated);
            
        }
        
        function generateDailyGraph($startDate, $endDate) {
            // Converts the dates to Unix Epoch form for the time graph.
            // INPUTS: Two dates in the form dd/mm/yy
            // Prevent Invalid Input
            if(!preg_match("/^\d{2}\/\d{2}\/\d{2}$/", $startDate)) { die("Invalid Input"); }
            if(!preg_match("/^\d{2}\/\d{2}\/\d{2}$/", $endDate)) { die("Invalid Input"); }
            // Convert date to timestamp
            $startDate = explode("/", $startDate);
            $endDate = explode("/", $endDate);
            $start = mktime(0,0,0,$startDate[1],$startDate[0],$startDate[2]);
            $end = mktime(0,0,0,$endDate[1],$endDate[0],$endDate[2]);
            $this->generateTimeGraph($start, $end);
        }
        
        
    
    }
    
    //$solarTesting = new modSolar();
    //$solarTesting->generateDailyGraph("06/06/2014", "09/06/2014")
    //print_r($solarTesting->currentPower());
    //print_r($solarTesting->getDailyUsage("09-06-2014"));
    
?>