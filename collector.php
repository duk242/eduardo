<?php
    // Collector
    // Spawns the individual module collectors as separate processes for 
    // TODO: Add a cleanup script caller that calls all the cleanup scripts.
    // TODO: Add debugging support + timestamps
    // TODO: Make this neat!    
    // Load in modules
    $db = new SQLite3('database.sqlite');
    $query = 'SELECT * FROM modules';
    $result = $db->query($query);
    $moduleCount = 0;
    
    while($row = $result->fetchArray()) {
        if($row['enabled'] == 1) {
            // Add the module to the load thingy
            echo "Module Loaded: ". $row['fullName'] ."\n";
            $modules[$moduleCount]['modName'] = $row['modName'];
            $modules[$moduleCount]['fullName'] = $row['fullName'];
            $moduleCount++;
        }
    }
    
    // This is to make the signals work properly.
    declare(ticks = 1);
    pcntl_signal(SIGINT, 'closeDown');
    pcntl_signal(SIGTERM, 'closeDown');
    
    // Shut down function. Quits all the children before dying itself.
    function closeDown() {
        echo "SHUTTING DOWN COLLECTOR! \n";
        // Kill child processes
        global $modules, $moduleCount;
        for($i = 0; $i < $moduleCount; $i++) {
            $fileName = "modules/".$modules[$i]['modName'] ."/".$modules[$i]['modName'] ."_collector.pid";
            if(!file_exists($fileName)) {
                echo "No pid file for module ". $modules[$i]['fullName'] .". Ignoring.\n";
            } else {
                $file = fopen($fileName, "r");
                $line = fgets($file);
                echo "Killing PID: $line belonging to ".$modules[$i]['fullName'] .".\n";
                posix_kill($line, SIGTERM);
                fclose($file);
            }
        }
        die("Exiting now!\n");
    }
    
    // Fire up children processes    
    for($i = 0; $i < $moduleCount; $i++) {
        $child = "modules/".$modules[$i]['modName']."/". $modules[$i]['modName'] ."_collector.php";
        if(!file_exists($child)) {
            echo "No _collector file for module: ". $modules[$i]['fullName'] .". Ignoring.\n";
        } else {
            popen("php $child > /usr/local/eduardo/modlog &", "r");     // Opens with the & to daemonise it.
        }
    }
    
    
    while(1) {
        // Keeps it alive doing nothing.
        // You can also put in things here to check if your other scripts are still alive or whatever.
        sleep(20);
        



        //posix_kill(posix_getpid(), SIGTERM); 
        
        
    }
    
?>