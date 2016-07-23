<?php

set_include_path("./../". PATH_SEPARATOR . ini_get("include_path"));

require_once 'vendor/autoload.php';
require_once 'src/CSVFeed.php';
require_once 'src/XMLFeed.php';
require_once 'src/JSONFeed.php';

	$files = '../tests/data';
	try {
// 		$reader = new CSVFeed("$files/FeelingSexy.txt", "\t");
		$reader = new CSVFeed("$files/MosquitNo.csv", ",");
// 		$reader = new JSONFeed("$files/stream1.json");
// 		$reader = new XMLFeed("$files/sample.xml", "item");
	} catch (Exception $e) {
		print_r($e->getMessage());
	}

	while ($reader->valid()) {
		try {
			// Reading a file
			$reader->next();
			$reader->current();
		} catch (Exception $e) {
			print_r($e->getMessage());
			continue;
		}
	}
