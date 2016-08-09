<?php

set_include_path("./../". PATH_SEPARATOR . ini_get("include_path"));

require_once 'vendor/autoload.php';
require_once 'CsvFileIterator.php';
require_once 'src/CSVFeed.php';

use PHPUnit\Framework\TestCase;

class CSVFeedTest extends TestCase {
	
	protected function setUp() {
	}

 	public function test() {
	}

	public function enumerator() {
// 		return new CsvFileIterator(__DIR__ . "/data/csv-example.csv");
		return new CSVFeed(__DIR__ . "/data/csv-example.csv", ",");
	}
	
	/**
	 * @dataProvider enumerator
	 */
 	public function testCurrent($input) {
//  		fwrite(STDOUT, print_r($input, TRUE));
		$this->assertNotEmpty($input);
	}
	
	protected function tearDown() {
	}

}
