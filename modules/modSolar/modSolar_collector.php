<?php
    declare(ticks = 1);     // Must declare this for SIGTERM to work right.
    include_once(__DIR__."/../moduleClass.php");
    class modSolar extends module {
        private $serverIP = "192.168.0.105";
        private $sleepTime = 10;                // Sleep time in seconds
        private $db;
        private $serverAddress;
        
    
        public function loopCode() {
            $solarData = file_get_contents($this->serverAddress);
            if($solarData) {
                // Clean/Add to DB
                preg_match("/<OutputPower>.*<\/OutputPower>/", $solarData, $data);
                $outputPower = str_replace("</OutputPower>", "", str_replace("<OutputPower>", "", $data[0]));
                $this->debugLog("Output Power:  ".$outputPower."kW"); 
                $query = "INSERT INTO solar Values(".time().",$outputPower)";
                $this->db->query($query);
            } else {
                $this->debugLog("Error Reading Solar data, will try again in ".$this->sleepTime ."seconds.");
            }
            sleep($this->sleepTime);

        }
        
        public function preLoop() {
            $dbLocation = __DIR__."/../../database.sqlite";
            $this->db = new SQLite3($dbLocation);
            $this->serverAddress = 'http://'.$this->serverIP.'/meters.xml';

        }

            
    
    }
    $butts = new modSolar("modSolar");
    $butts->setDebug(1);
    
    $butts->startLoop();
    
?>