<?php

set_include_path("./../". PATH_SEPARATOR . ini_get("include_path"));

require_once 'vendor/autoload.php';
require_once 'src/Feed.php';

error_reporting ( E_ALL );
ini_set ( 'display_errors', 'On' );
ignore_user_abort ( true );
set_time_limit ( 60 * 60 * 30 );
ini_set ( 'memory_limit', '1200M' );

/**
 * TODO : Using XMLReader for cursor and XMLParser to parse each element
 */
//	http://php.net/manual/en/class.domnode.php
class XMLFeed extends Feed {
	private $_items_iterator;
	
	function __construct($file, $recordLabel) {
		parent::__construct();

	/*	$file = file_get_contents($contentFile['products']);
		$htmlDom = new DOMDocument();
		$htmlDom->loadXML($file);
		$this->_file_handler = $htmlDom->getElementsByTagName($contentFile['record']);	*/

		$file = file($file);
		$file = implode("", $file);
		$parser = xml_parser_create();
		xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
		xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
		xml_parse_into_struct($parser, $file, $this->_file_handler, $this->_items_iterator);
		xml_parser_free($parser);
				
		if (isset($recordLabel))
			$this->_items_iterator = $this->_items_iterator[$recordLabel];
		else throw new Exception("Error: Not defined delimiter");
		$this->_items_iterator = new ArrayIterator(array_chunk($this->_items_iterator, 2));
	}
	
	function valid() {
		if (parent::valid())
			return $this->_items_iterator->valid();
		//	return !is_null($this->_file_handler->item($this->_record_index));
	}

	function next() {
		parent::next();
	//	$product = $this->_file_handler->item($this->_record_index);
		
		$range = $this->_items_iterator->current();
		$this->_items_iterator->next();

		$item = array_slice($this->_file_handler, $range[0]+1, $range[1]-$range[0]-1);
		$prefix = "";
		foreach ($item as $field) {
			if (strcmp($field['type'], 'open') == 0)
				$prefix = $field['tag'] . '_';
		
			if (strcmp($field['type'], 'close') == 0)
				$prefix = "";
		
			if (strcmp($field['type'], 'complete') == 0)
				$this->_entity[$prefix . $field['tag']] = (isset($field['value']) ? $field['value'] : null);
		}
	}

	function createParser() {
		$parser = xml_parser_create_ns(NULL, self::NS_SEPARATOR);
		xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, FALSE);
		xml_set_object($parser, $this);
		xml_set_element_handler($parser, 'startElement', 'endElement');
		xml_set_character_data_handler($parser, 'characterData');
		return $parser;
	}

	public static function sample() {
		$reader = new XMLReader();
		
		// load the selected XML file to the DOM
		if (!$reader->open('../../tests/crawl/sample.xml')) {
			die("Failed to open the file");
		}
		
		while ($reader->read()) {
			new dBug($reader->name);
// 			new dBug($reader->nodeType);

			if ($reader->nodeType == XMLReader::ELEMENT && $reader->name === 'item'){
				new dBug("product");
			/*	$xml = simplexml_load_string($reader->readOuterXML());
				$productcode = (string)$xml->a001;
				$title = (string)$xml->title->b203;
				$author = (string)$xml->contributor->b037;
				$language = (string)$xml->language->b252;
				$category = $xml->subject->b069;
				$description = (string)$xml->othertext->d104;
				$publisher = (string)$xml->publisher->b081;
				$pricecover = (string)$xml->supplydetail->price->j151;
				$salesright = (string)$xml->salesrights->b090;
			
				@$productcode1 = htmlentities($productcode,ENT_QUOTES,'latin1_swedish_ci');
				@$title1 = htmlentities($title,ENT_QUOTES,'latin1_swedish_ci');
				@$author1 = htmlentities($author,ENT_QUOTES,'latin1_swedish_ci');
				@$language1 = htmlentities($language,ENT_QUOTES,'latin1_swedish_ci');
				@$category1 = htmlentities($category,ENT_QUOTES,'latin1_swedish_ci');
				@$description1 = htmlentities($description,ENT_QUOTES,'latin1_swedish_ci');
				@$publisher1 = htmlentities($publisher,ENT_QUOTES,'latin1_swedish_ci');
				@$pricecover1 = htmlentities($pricecover,ENT_QUOTES,'latin1_swedish_ci');
				@$salesright1 = htmlentities($salesright,ENT_QUOTES,'latin1_swedish_ci');	*/
			}

			$reader->next();
		}

		return;
	}
	
}
