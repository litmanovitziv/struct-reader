<?php

namespace DataPipeline;

set_include_path("./../../". PATH_SEPARATOR . ini_get("include_path"));

require_once 'vendor/autoload.php';
require_once 'src/reader/Reader.php';

class JSONReader extends Reader {

	function __construct($file, $debug = null) {
		parent::__construct($debug);

		if (($this->_file_handler = fopen($file, "r")) == false)
			throw new \Exception("Error: Invalid file");
	}

	function __destruct() {
		fclose($this->_file_handler);
	}
	
	function valid() {
		if (parent::valid())
			return !feof($this->_file_handler);
	}
	
	function next() {
		if (($this->_entity = fgets($this->_file_handler)) == false)
			throw new \Exception("Error: product failed on read");
		if (($this->_entity = json_decode($this->_entity, true)) === null)
			throw new \Exception("Error: unrecognized type");
		parent::next();
	}

	function rewind() {
		parent::rewind();
		rewind($this->_file_handler);
	}
	
}
