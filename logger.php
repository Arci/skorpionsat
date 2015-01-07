<?php

class Logger {
	private static $filePrefix = "log_";
	private static $instance;
	private $name;
	private $fileDescriptor;
	private $isWriting = false;
		
	static function getLogger() {
		if(self::$instance == null){
			self::$instance = new Logger("logger");
		}
		return self::$instance;
	}
	
	private function __construct($name) {
		$this->name = $name;
		date_default_timezone_set('Europe/Rome');
		$this->fileDescriptor = fopen(LOG_DIR . self::$filePrefix . date("Ymd", time()) . ".log", "a+");
	}
	
	private function buildLogEntry($type, $clazz, $message){
		while($this->isWriting);
		$this->isWriting = true;
		fwrite($this->fileDescriptor, "[" . date("D M d \@ H:i:s Y", time()) . "] [".$type."] [" . $clazz. "]: " . $message . "\n");
		$this->isWriting = false;
	}
	
	public function query($clazz, $message) {
		self::buildLogEntry("QUERY", $clazz, $message);
	}
	
	public function debug($clazz, $message) {
		self::buildLogEntry("DEBUG", $clazz, $message);
	}
	
	public function error($clazz, $message) {
		self::buildLogEntry("ERROR", $clazz, $message);
	}
}

?>