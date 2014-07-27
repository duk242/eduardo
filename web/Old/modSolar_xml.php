<?php
    // XML Responder
    header("Content-type: application/xml");
    include_once('../modules/modSolar/modSolar_data.php');
    $solar = new modSolar();
    $power = $solar->currentPower();
     
    echo "<?xml version=\"1.0\" encoding=\"utf-8\" ?>
    <powerThing>
    <time>".$power[0]."</time>
    <power>".$power[1]."</power>
    </powerThing>
    ";
    //echo "<time>".$power[0]."</time>\n";
    //echo "<power>".$power[1]."</power>\n";
?>