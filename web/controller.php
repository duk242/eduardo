<?php
    // Controller Page
    
    function checkProcess($modName) {
        $file = @fopen("../modules/$modName/".$modName ."_collector.pid", "r");
        if($file) {
            $pid = fgets($file);
        } else {
            return "NOPID";
        }
        if(!$pid) {
            return "NOPID";
        }
        if (shell_exec("ps aux | grep " . $pid . " | wc -l") > 0) {
            return "YES";
        } else {
            return "NO";
        }
    }
    
    // Work out loaded modules
    $db = new SQLite3('../database.sqlite');
    $query = 'SELECT * FROM modules';
    $result = $db->query($query);
    $moduleCount = 0;
    
    while($row = $result->fetchArray()) {
        // Add the module to the load thingy
        //echo "Module Loaded: ". $row['fullName'] ."\n";
        $modules[$moduleCount]['modName'] = $row['modName'];
        $modules[$moduleCount]['fullName'] = $row['fullName'];
        $modules[$moduleCount]['enabled'] = $row['enabled'];
        $moduleCount++;
    }
    // Determine if modules are running
    print '<html><head><title>Module Controller</title></head><body>
    <p>Controller is Up/down</p>
    <p><table><tr><td>ModName</td><td>Full Name</td><td>Enabled</td><td>Running</td></tr>';
    
    for($i = 0; $i < $moduleCount; $i++) {
        print '<tr><td>'. $modules[$i]['modName'] .'</td><td>'. $modules[$i]['fullName'] .'</td>';
        if($modules[$i]['enabled'] == 1) {
            print '<td>Yes</td>';
        } else {
            print '<td>No</td>';
        }
        $running = checkProcess($modules[$i]['modName']);
        if($running == "YES") {
            print '<td>Yes</td>';
        } else if($running == "NO") {
            print '</td>No</td>';
        } else if($running == "NOPID") {
            print '<td>No PID File</td>';
        } else {
            print '<td>IDK My BFF Jill</td>';
        }
    }
    print '</tr></table></body></html>';

    // Print list of modules 
?>