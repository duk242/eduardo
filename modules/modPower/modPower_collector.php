<?php
    declare(ticks = 1);     // Must declare this for SIGTERM to work right.
    error_reporting(~E_WARNING);
    include_once(__DIR__."/../moduleClass.php");
    class modPower extends module {
        private $db;
        private $sock;

        public function loopCode() {
            //Receive some data
            $r = socket_recvfrom($this->sock, $buf, 512, 0, $remote_ip, $remote_port);
            //echo "$remote_ip : $remote_port -- " . $buf;
            if($buf) {
                preg_match("/<current>.*<\/current>/", $buf, $data);
                $current = str_replace("</current>", "", str_replace("<current>", "", $data[0]));
                
                preg_match("/<time>.*<\/time>/", $buf, $data);
                $time = str_replace("</time>", "", str_replace("<time>", "", $data[0]));
                $query = "INSERT INTO power VALUES(".time().",$current,$time)";
                $this->db->query($query);
                $this->debugLog("Current: $current kWh and MS: $time");
            }

        }
        
        public function preLoop() {
            $dbLocation = __DIR__."/../../database.sqlite";
            $this->db = new SQLite3($dbLocation);
            if(!($this->sock = socket_create(AF_INET, SOCK_DGRAM, 0))) {
                $errorcode = socket_last_error();
                $errormsg = socket_strerror($errorcode);
                
                die("Couldn't create socket: [$errorcode] $errormsg \n");
            }
            // Bind the source address
            if( !socket_bind($this->sock, "0.0.0.0" , 13333) )
            {
                $errorcode = socket_last_error();
                $errormsg = socket_strerror($errorcode);
                
                die("Could not bind socket : [$errorcode] $errormsg \n");
            }
            


        }

    }
    $butts = new modPower("modPower");
    $butts->setDebug(1);
    $butts->startLoop();
    
?>