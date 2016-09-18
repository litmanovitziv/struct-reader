<?php

namespace DataPipeline;

set_include_path("./../" . PATH_SEPARATOR . ini_get("include_path"));

require_once 'vendor/autoload.php';
require_once 'src/reader/CSVReader.php';
//require_once 'src/XMLReader.php';
require_once 'src/reader/JSONReader.php';
require_once 'src/Env.php';

	$argv = getopt("s:d:i:", array("debug::", "limit::"));
	var_dump($argv);
	$folderPath = $argv['d'];
	if (! file_exists($folderPath))
		if (! mkdir($folderPath, 0777, true))
			throw new \Exception('Failed to create folders...' . $folderPath);
	
	$file = $argv['s'];
	$output_file = substr($file, 0, strpos($file, "."));
	$env = new Env($argv);
	try {
		if (php_sapi_name() == 'cli')
			switch ($argv['i']) {
				case "tsv":
					$reader = new CSVReader($file, "\t", $env);
					break;
				case "csv":
					$reader = new CSVReader($file, ",", $env);
					break;
				case "json":
					$reader = new JSONReader($file, $env);
					break;
				default:
					throw new \Exception("Error: file wasn't choden");
			}
	} catch (\Exception $e) {
		echo $e->getMessage() . PHP_EOL;
		exit(1);
	}

	if (isset($reader))
		while ($reader->valid()) {
			try {
				// Reading a file
				$reader->next();
				$reader->current();
			} catch (\Exception $e) {
				echo $e->getMessage() . PHP_EOL;
				continue;
			}
		}
