<?php
function grabDailyLog() {
    // Grabs the daily log from the solar unit, cleans it and adds it to the database.
    // TODO: Add Error checking to make sure the file is legit.
    $scriptDirectory = dirname(__FILE__);
    $dbLocation = "$scriptDirectory/../../database.sqlite";
    $db = new SQLite3($dbLocation);
    $db->busyTimeout(10000);     // Stops silly locking issues. 10000=10s timeout. 
    
    $dailyLogLocation = 'http://192.168.0.105/log_daily.csv';
    $dailyLogFile = fopen($dailyLogLocation, "rb");
    if($dailyLogFile) {
        fgets($dailyLogFile);   // Skipping first 2 lines cause they're junk
        fgets($dailyLogFile);
        while(($line = fgets($dailyLogFile)) !== false) {
            $exploded = explode("\t", $line);
            if(@$exploded[1]) {  // Accounts for the last line being a space.
                $dateSplode = explode("-", $exploded[0]);
                $niceDate = $dateSplode[2] ."-". $dateSplode[1] ."-".$dateSplode[0];
                // The fuck is this. 
                $niceDate = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $niceDate);
                $nicePower = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $exploded[1]);
                
                $query = 'SELECT * FROM solarDaily WHERE solarDate="'. $niceDate .'"';
                $dbCheck = $db->query($query); 
                if($dbCheck->fetchArray()) {
                    // Already Exists, skip it.
                } else {
                    // Add it to the DB
                    $query = 'INSERT INTO solarDaily (solarDate, power) VALUES ("'.$niceDate.'","'.$nicePower.'")';
                    $insertValues = $db->query($query);
                    echo "Inserted $niceDate and ". $exploded[1] ."\n";
                } 
            }
        }
    } else {
        // File Read Error
        die("File Read Error. Sad sad sad face.");
    }
    fclose($dailyLogFile);
}

grabDailyLog();

?>   