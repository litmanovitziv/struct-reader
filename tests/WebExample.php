<?php

namespace DataPipeline;

set_include_path("./../" . PATH_SEPARATOR . ini_get("include_path"));

require_once 'vendor/autoload.php';
require_once 'src/reader/CSVReader.php';
//require_once 'src/XMLReader.php';
require_once 'src/reader/JSONReader.php';
require_once 'src/Env.php';

	$file = __DIR__ . "/data";
	try {
		if (isset($_REQUEST['input']))
			switch ($_REQUEST['input']) {
				case "tsv":
					$reader = new CSVReader("$file/tsv-example.txt", "\t", new Env($_REQUEST));
					break;
				case "csv":
					$reader = new CSVReader("$file/csv-example.csv", ",", new Env($_REQUEST));
					break;
				case "json":
					$reader = new JSONReader("$file/json-example.json", new Env($_REQUEST));
					break;
				default:
					throw new Exception("Error: file wasn't choden");
			}
	} catch (Exception $e) {
		new \dBug($e->getMessage());
		exit(1);
	}

	if (isset($reader))
		while ($reader->valid()) {
			try {
				// Reading a file
				$reader->next();
				$reader->current();
			} catch (Exception $e) {
				new \dBug($e->getMessage());
				continue;
			}
		}
