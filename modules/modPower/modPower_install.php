<?php
    // Solar Module Installer
    // Author: Dustin Kerr
    // Created: 23/2/14     Modified: 23/2/14
    // Should only be called once, but if called again it will recreate any missing tables.
    $scriptDirectory = dirname(__FILE__);
    $dbLocation = "$scriptDirectory/../../database.sqlite";
    
    
    $db = new SQLite3($dbLocation);

    $query = "CREATE TABLE power(time REAL PRIMARY KEY, power REAL, millis REAL)";
    $results = $db->query($query);
    if(!$results) { 
        echo("Power Database Could not be created because: ". $db->lastErrorMsg() ."\n"); 
    } else {
        echo "Power Data Table Made.\n";
    }
    
    // Second Table for average power stuff
    $query = "CREATE TABLE power_average(time REAL PRIMARY KEY, powerAverage REAL, usage REAL)";
    $results = $db->query($query);
    if(!$results) { 
        echo("PowerAverage Database Could not be created because: ". $db->lastErrorMsg() ."\n"); 
    } else {
        echo "PowerAverage Data Table Made.\n";
    }
    
    // Check for module in the first place :o
    $query = 'SELECT * FROM modules WHERE modName="modPower"';
    $dbCheck = $db->query($query);
    if($dbCheck->fetchArray()) {        // fetchArray returns 0 if there's nothing in the table.
        echo "Module modPower is already added to the module table. Skipping.\n";
    } else {
        $query = 'INSERT INTO modules (modName, enabled, fullName) VALUES ("modPower","1","Arduino Power Module")';
        $results2 = $db->query($query);
        if(!$results2) { 
            echo ("Could not add module to module table because: ". $db->lastErrorMsg() ."\n"); 
        } else {
            echo "Module added to module Table.\n"; 
        }
    }
?>