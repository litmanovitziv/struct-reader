<?php

set_include_path("./../". PATH_SEPARATOR . ini_get("include_path"));

require_once 'vendor/autoload.php';
require_once 'src/CSVFeed.php';
//require_once 'src/XMLFeed.php';
require_once 'src/JSONFeed.php';

	$file = __DIR__ . "/data";
	try {
		if (isset($_REQUEST['input']))
			switch ($_REQUEST['input']) {
				case "tsv":
					$reader = new CSVFeed("$file/tsv-example.txt", "\t");
					break;
				case "csv":
					$reader = new CSVFeed("$file/csv-example.csv", ",");
					break;
				case "json":
					$reader = new JSONFeed("$file/json-example.json");
					break;
				default:
					throw new Exception("Error: file wasn't choden");
			}
	} catch (Exception $e) {
		new dBug($e->getMessage());
		exit(1);
	}

	if (isset($reader))
		while ($reader->valid()) {
			try {
				// Reading a file
				$reader->next();
				$reader->current();
			} catch (Exception $e) {
				new dBug($e->getMessage());
				continue;
			}
		}
