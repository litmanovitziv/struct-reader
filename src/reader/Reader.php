<?php

namespace DataPipeline;

// http://localhost/crawl/read/<type>Feed.php?debug=true,print,test,limit&limit=1
set_include_path("./../../". PATH_SEPARATOR . ini_get("include_path"));

require_once 'vendor/autoload.php';
require_once 'src/Env.php';

abstract class Reader implements \Iterator {
	protected $_file_handler;
	protected $_entity;
	protected $_record_index;
	protected $_env;

	/**
	 * TODO : adding debug configuration
	 * @param array $debug_config
	 */
	function __construct($debug = null) {
		$this->_record_index = 0;
		$this->_env = $debug;
	}
	
	function getEntity() {
		return $this->_entity;
	}

	function rewind() {
		$this->_record_index = 0;
	}
	
	function valid() {
		if ($this->_env->isDebugEnv() & ENV_DEBUG_LIMITED) {
			if ($this->_record_index > $this->_env->getMaxIterarions()) {
				$this->_record_index = 0;
				echo "Warning : Stopped because of limit is over" . PHP_EOL;
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
		if ($this->_env->isDebugEnv() & ENV_DEBUG_PRINTED) {
			echo "record no. $this->_record_index" . PHP_EOL;
			var_dump($this->_entity);
			echo PHP_EOL;
		}

		return $this->_entity;
	}

}
