<?php
//TODO
class Logger {
	private static $fileName = "log_";
	private static $instance;
	private $name;
	private $fileDescriptor;
	private $isWriting = false;
	
	static function getLogger() {
		if(self::$instance == null) self::$instance = new Logger("logger");
		return self::$instance;
	}
	
	private function __construct($name) {
		$this->name = $name;
		$this->fileDescriptor = fopen("log/" . self::$fileName . date("Ymd", time()), "a+");
	}
	
	function debug($clazz, $str) {
		if (DEBUG){
			while($this->isWriting);
			$this->isWriting = true;
			fwrite($this->fileDescriptor, date("Y-m-d H:i:s", time()) . " DEBUG-" . $clazz . ": " . $str . "\n");
			$this->isWriting = false;
		}
	}
}

?>