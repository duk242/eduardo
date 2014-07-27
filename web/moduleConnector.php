<?php
    // This file accepts an array with the values:
    //  1 - Module Name
    //  2 - Function Name
    // it will then call the file modName_data.php?n=modName&f=modFunction file to grab the info, and return it in JSON form for AJAX stuff.
    header('Content-Type: application/json');
    
    // TODO Sanitise Inputs

    $modName = $_GET['n'];
    $modFunction = $_GET['f'];
    @$modArguments = $_GET['a']; // Optional extra
    
    include_once("../modules/". $modName ."/". $modName ."_data.php");
    $$modName = new $modName();
    $function = $$modName->$modFunction($modArguments);
    
    echo json_encode($function);
    //echo "lol this worked.";
    
    
    
?>