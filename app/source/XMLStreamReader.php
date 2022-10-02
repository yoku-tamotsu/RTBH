<?php

use Prewk\XmlStringStreamer;
use Prewk\XmlStringStreamer\Parser;
use Prewk\XmlStringStreamer\Stream;
use Prewk\XmlStringStreamer\Stream\File;



class XMLStreamReader {
	
	private $parser;
	private $inputStream;
	private $progressPrinterMethod;
	private $totalDataSize;
	
	function __construct(){
		$this->parser = new Parser\StringWalker();
		
	}
	
	function setScanNodeName(string $nodeName){
		if($nodeName == null){
			$this->parser = new Parser\StringWalker();
			return;
		}
		
		$this->parser = new Parser\UniqueNode(array("uniqueNode" => $nodeName));
	}
	
	function setSourceFile(string $sourceFilePath){
		$totalDataSize = filesize($sourceFilePath);
		$this->inputStream = new File($sourceFilePath, 16384, function($chunk, $readBytes) use ($totalDataSize) {
			if($this->progressPrinterMethod == null)
				return;
			
			$cb = $this->progressPrinterMethod;
			$cb(round(100 * $readBytes / $totalDataSize, 2));
			
			//$this->progressPrinterMethod(round(100 * $readBytes / $totalDataSize, 2));
		});
	}

	function setProgressPrinter(callable $progressPrinter){
		$this->progressPrinterMethod = $progressPrinter;
	}
		
	function scan(callable $nodeEater){
		if($nodeEater == null)
			return;
		
		$inputStreamer = new XmlStringStreamer($this->parser, $this->inputStream);
		
		while ($rawNode = $inputStreamer->getNode()) {
			$node = simplexml_load_string($rawNode);
			
			if($nodeEater($node) === false)
				break;
		}
	}
}