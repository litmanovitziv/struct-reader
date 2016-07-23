<?php

// http://localhost/crawl/read/<type>Feed.php?debug=true,print,test,limit&limit=1
set_include_path("./../". PATH_SEPARATOR . ini_get("include_path"));

require_once 'vendor/autoload.php';
require_once 'src/Env.php';

abstract class Feed implements Iterator {
	protected $_file_handler;
	protected $_entity;
	protected $_record_index;

	/**
	 * TODO : adding debug configuration
	 * @param array $debug_config
	 */
	function __construct($debug_config = null) {
		$this->_record_index = 0;
	}
	
	function getEntity() {
		return $this->_entity;
	}

	function rewind() {
		$this->_record_index = 0;
	}
	
	function valid() {
		if (isDebugEnv() & ENV_DEBUG_LIMITED) {
			if ($this->_record_index > getMaxIterarions()) {
				$this->_record_index = 0;
				echo "Warning : Stopped because of limit is over<br />";
				return false;
			}
		}
		
		return true;
	}
	
	function next() {
		$this->_record_index++;
	}

	function key() {
		return $this->_record_index;
	}
	
	function current() {
		if (isDebugEnv() & ENV_DEBUG_PRINTED) {
			new dBug("record no. $this->_record_index");
			new dBug($this->_entity);
		}
	}

}
