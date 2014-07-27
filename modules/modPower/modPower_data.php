<?php
    // modPower_data.php
    // Controls output/reading of data from the database.
    // Author: Dustin Kerr
    // Date Created: 17/5/14    Modified: 14/6/14
    
    class modPower {
        private $db;
    
        function __construct() {
            // Connect to the Database
            $dbLocation = dirname(__FILE__)."/../../database.sqlite";
            $this->db = new SQLite3($dbLocation);
            $this->db->busyTimeout(10000);     // Stops silly locking issues. 10000=10s timeout. 
        }
        
        function powerNow() {
            // Retrieves the power right now
            // RETURNS: Array: power=>1.5 (in kW), time=>1402732305 (unix epoch), timeSinceLast=>2 (seconds)
            $query = "SELECT * FROM power ORDER BY time DESC LIMIT 1";
            $result = $this->db->query($query);
            $latestPower = $result->fetchArray();
            $timeNow = time();
            $timeSinceLast = $timeNow - $latestPower['time'];
            return array("power"=>$latestPower['power'],
                "time"=>$latestPower['time'],
                "timeSinceLast"=>$timeSinceLast);
        }
        
        function powerCalc($startTime, $finishTime) {
            // Calculate Power Usage between Start/Finish Time
            // INPUTS:  $startTime = 1402732305 (unix epoch), $finishTime = 1402732305 (unix epoch)
            // OUTPUTS: Keyed Array: power=>15.5 (Power in kWh)

            // Prevent Invalid Input
            if(!preg_match("/\d+/", $startTime)) { die("Invalid Input"); }
            if(!preg_match("/\d+/", $finishTime)) { die("Invalid Input"); }
            // Convert to microtime since that's how the DB has it
            // ^^^ Or not, idk wtf?
            $start = $startTime;
            $finish = $finishTime;
            
            $query = "SELECT * FROM power WHERE time >= $start AND time <= $finish";
            $result = $this->db->query($query);
            $i = 0;
            while($line = $result->fetchArray()) {
                $i++;
            }
            $powerUsage = $i / 3200;
            
            return array("power" => $powerUsage);
        }
        
        function powerCalcDay($date) {
            // Prepares the dates for the powerCalc function
            // INPUTS: $date = dd/mm/yy
            // RETURNS: the result from powerCalc
            $calcDate = explode("/", $date);
            $startTime = mktime(0,0,0,$calcDate[1],$calcDate[0],$calcDate[2]);
            $endTime = mktime(23,59,59,$calcDate[1],$calcDate[0],$calcDate[2]);
            $result = $this->powerCalc($startTime, $endTime);
            return $result;
        }
    }
    
    //$power = new modPower();
    //print_r($power->powerNow());
    
    //echo $power->powerCalc("1400105660","1400522005") . " kWh\n";
    //echo $power->powerCalcDay("8/6/14") . "kWh\n";
    
?>