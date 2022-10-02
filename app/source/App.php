<?php

class App {
	private $sourceFile;
	private $outputFile;
	private $day;
	private $hour;
	private $minute;
	public $scanInfo;
	
	function __construct(){
		$this->day = -1;
		$this->hour = -1;
		$this->minute = -1;
		$this->scanInfo = [-1, -1];
	}
	
	function setSourceFile(string $sourceFilePath){
		$this->sourceFile = $sourceFilePath;
	}
	
	function setOutputFile(string $outputFilePath){
		$this->outputFile = $outputFilePath;
	}
	
	function setTime(int $day, int $hour, int $minute){
		$this->day = $day;
		$this->hour = $hour;
		$this->minute = $minute;
	}
	
	function run(){
		clearstatcache();
		
		if(!is_file($this->sourceFile))
			throw new Exception("Source file not exist or it is not regular file");
		
		if(is_dir($this->outputFile))
			throw new Exception("Output path is directory - required regular file or nothing");
		
		if($this->hour < 0 || $this->hour > 23 || $this->minute < 0 || $this->minute > 59){
			$this->hour = intval(gmdate("H"));
			$this->minute = intval(gmdate("i"));
		}
		
		if($this->day < 1 || $this->day > 7)
			$this->day = intval(gmdate("N"));
		
		#
		
		$outputStreamer = new XMLStreamWriter($this->outputFile, true);
		$outputStreamer->startElement('offers');

		#

		$inputStreamer = new XMLStreamReader();
		$inputStreamer->setScanNodeName('offer');
		$inputStreamer->setSourceFile($this->sourceFile);
		$inputStreamer->setProgressPrinter(function($percent){
			echo "Progress: {$percent}%               \r";
		});
		
		$this->nodeCounter = 0;
		$this->activeNodeCounter = 0;

		$inputStreamer->scan(function($offerNode) use ($outputStreamer){
			$openings = json_decode($offerNode->opening_times, true);
			$isActive = $this->isOfferActive($openings);
			$offerNode->addChild('is_active', "<![CDATA[" . ($isActive ? 'true' : 'false') . "]]>");
			$outputStreamer->writeNode($offerNode);
			
			$this->nodeCounter++;
			
			if($isActive)
				$this->activeNodeCounter++;
		});

		#

		$outputStreamer->endElement();
		$outputStreamer->close();
		$this->scanInfo = [$this->nodeCounter, $this->activeNodeCounter, "Day {$this->day}, Hour {$this->hour}:{$this->minute}"];
	}
	
	private function isOfferActive(Array $openings):bool {
		if(!array_key_exists($this->day, $openings))
			return false;
		
		$cdOpens = $openings[$this->day];
		$time = strtotime("{$this->hour}:{$this->minute}");
		
		foreach($cdOpens as $timeWindow){
			$to = strtotime($timeWindow['opening']);
			$tc = strtotime($timeWindow['closing']);
			
			if($to < $time && $tc > $time)
				return true;
		}

		return false;
	}
	
	
}