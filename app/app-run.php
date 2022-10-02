#!/usr/local/bin/php
<?php

require_once __DIR__ . '/vendor/autoload.php';

include_once __DIR__ . '/source/App.php';
include_once __DIR__ . '/source/XMLStreamWriter.php';
include_once __DIR__ . '/source/XMLStreamReader.php';

#=======================================================================================

$sourceFile = "/data/feed.xml";
$outputFile = "/data/feed_out.xml";

#---------------------------------------------------------------------------------------

try {
	$app = new App();
	$app->setSourceFile($sourceFile);
	$app->setOutputFile($outputFile);
	$app->run();
	
	$si = $app->scanInfo;
	$total = $si[0];
	$active = $si[1];
	$paused = $total - $active;
	
	echo "\nScan at {$si[2]}\n";
	echo "Offers status: Total {$total}; Active {$active}; Paused {$paused};\n";
}catch(Exception $e){
	echo "Exception occured: {$e->getMessage()}\n";
}

echo "\n";

#=======================================================================================



