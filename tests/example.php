<?php

set_include_path("./../". PATH_SEPARATOR . ini_get("include_path"));

require_once 'vendor/autoload.php';
require_once 'src/CSVFeed.php';
require_once 'src/XMLFeed.php';
require_once 'src/JSONFeed.php';

	$files = './data';
	try {
		if (isset($_REQUEST['input']))
			switch ($_REQUEST['input']) {
				case "tsv":
					$reader = new CSVFeed("$files/tsv-example.txt", "\t");
					break;
				case "csv":
					$reader = new CSVFeed("$files/csv-example.csv", ",");
					break;
				case "json":
					$reader = new JSONFeed("$files/json-example.json");
					break;
				case "xml":
					$reader = new XMLFeed("$files/xml-example.xml", "item");
					break;
				default:
					throw new Exception("Error: file wasn't choden");
			}
	} catch (Exception $e) {
		print_r($e->getMessage());
		exit(1);
	}

	if (isset($reader))
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
