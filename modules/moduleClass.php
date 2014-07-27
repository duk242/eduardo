<?php
    class module {
        private $modName;
        private $debugFlag = 0;
        private $pidFile;
        
        public function startLoop() {
            while(1) {
                $this->loopCode();
            }
        }
        
        public function loopCode() {
            // Nothing here, override this function!
            // Put your code that you want to loop in here.
        }
        
        public function preLoop() {
            // Override this one!
            // Runs just after the constructor
        }
        
        public function postLoop() {
            // Override this!
            // Runs when the destructor is called.
        }
        
        public function setDebug($value) {
            $this->debugFlag = $value;
        }
        
        public function debugLog($logThis) {
            if ($this->debugFlag) {
                $logFile = fopen(__DIR__."/".$this->modName."/".$this->modName.".log", "a");
                fwrite($logFile, date("d/m/y H:i:s")." - " .$logThis."\n");
                fclose($logFile);
            }
        }
        
        function createPID() {
            $pidFile = fopen($this->pidFile, "w");
            fwrite($pidFile, posix_getpid());
            fclose($pidFile);
        }
        
        function deletePID() {
            unlink($this->pidFile);
            //echo "Deleted: ".$this->pidFile;
        }
        
        function __construct($modName) {
            $this->modName = $modName;
            $this->pidFile = __DIR__."/".$this->modName."/".$this->modName."_collector.pid";
            $this->createPID();
            pcntl_signal(SIGINT, array($this, "shutdown"));
            pcntl_signal(SIGTERM, array($this, "shutdown"));
            $this->preLoop();
            // Setup System calls for terminate etc
        }
        
        function shutdown($sig) {
            // Mission and a half.
            die();
        }
        
        
        
        function __destruct() {
            $this->debugLog("Shutting Down");
            $this->deletePID();
            
        } 
    }

?>