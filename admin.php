<?php
    // Admin file derp
    
    // Installation functions here
    function installDB() {
        $db = new SQLite3('database.sqlite');
        
        
        // Stuff for Solar thingy
        $query = "CREATE TABLE modules(id INTEGER PRIMARY KEY AUTOINCREMENT, modName TEXT, enabled INTEGER, fullName TEXT)";
        $results = $db->query($query);
        if(!$results) { die("Derp."); }
        echo "DB Made.";
    }
    
    //installDB();
    
    //$db = new SQLite3('database.sqlite');
    
    //        $query = 'INSERT INTO modules (modName, enabled, fullName) VALUES ("modSolar","1","EnaSolar Module")';
    //    $results2 = $db->query($query);
?>