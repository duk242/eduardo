<?php
    // Solar Module Installer
    // Author: Dustin Kerr
    // Created: 23/2/14     Modified: 23/2/14
    // Should only be called once, but if called again it will recreate any missing tables.
    $scriptDirectory = dirname(__FILE__);
    $dbLocation = "$scriptDirectory/../../database.sqlite";
    
    
    $db = new SQLite3($dbLocation);

    $query = "CREATE TABLE solar(time INTEGER PRIMARY KEY, power REAL)";
    $results = $db->query($query);
    if(!$results) { 
        echo("Solar Database Could not be created because: ". $db->lastErrorMsg() ."\n"); 
    } else {
        echo "Solar Data Table Made.\n";
    }
    
    $query = "CREATE TABLE solarDaily(solarDate TEXT PRIMARY KEY, power REAL)";
    $results = $db->query($query);
    if(!$results) { 
        echo("Solar Daily Database Could not be created because: ". $db->lastErrorMsg() ."\n"); 
    } else {
        echo "Solar Daily Data Table Made.\n";
    }
    
    // Check for module in the first place :o
    $query = 'SELECT * FROM modules WHERE modName="modSolar"';
    $dbCheck = $db->query($query);
    if($dbCheck->fetchArray()) {        // fetchArray returns 0 if there's nothing in the table.
        echo "Module modSolar is already added to the module table. Skipping.\n";
    } else {
        $query = 'INSERT INTO modules (modName, enabled, fullName) VALUES ("modSolar","1","EnaSolar Module")';
        $results2 = $db->query($query);
        if(!$results2) { 
            echo ("Could not add module to module table because: ". $db->lastErrorMsg() ."\n"); 
        } else {
            echo "Module added to module Table.\n"; 
        }
    }
?>