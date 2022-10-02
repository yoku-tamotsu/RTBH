<?php

class XMLStreamWriter extends XMLWriter {
	
	private $dataCounter;
	private $overwriteFile;
	private $outputFilePath;
	
	function __construct(string $filePath, bool $overwrite){
		$this->dataCounter = 0;
		$this->overwriteFile = $overwrite;
		$this->outputFilePath = $filePath;
		$this->openMemory();
		$this->startDocument('1.0', 'UTF-8');
	}
	
	function writeNode(SimpleXMLElement $node){
		$data = explode("\n", html_entity_decode($node->asXML()), 2)[1];
		$this->writeRaw($data);
		$this->dataCounter += mb_strlen($data, '8bit');
		
		if($this->dataCounter < 16384)
			return;
		
		$this->flushStream();
		$this->dataCounter = 0;
	}
	
	function flushStream(){
		if($this->overwriteFile){
			file_put_contents($this->outputFilePath, "");
			$this->overwriteFile = false;
		}
		
		file_put_contents($this->outputFilePath, $this->flush(true), FILE_APPEND);
	}
	
	function close(){
		$this->flushStream();
	}	
}