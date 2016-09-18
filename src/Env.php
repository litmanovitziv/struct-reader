<?php

namespace DataPipeline;

//define('NEW_LINE', '<br />');
define('NEW_LINE', "<br />\r\n");

define("ENV_DEBUG", 1 << 8);
define("ENV_DEBUG_PRINTED", 1);
define("ENV_DEBUG_LIMITED", 1 << 1);
define("ENV_DEBUG_TESTED", 1 << 2);
define("ENV_DEBUG_IMPORT", 1 << 3);
define("ENV_DEBUG_EXPORT", 1 << 4);

class Env {
	private $_env;

	function __construct($debug = array()) {
		foreach ($debug as $test_opt => &$value) {
			if (is_numeric($value))
				$value = intval($value);
			else {
				$value = explode(',', $value);
// 				if (count($value) <= 1)
// 					$value = $value[0];
			}
		}
		$this->_env = $debug;
	}
	
	function getEnv() {
		return $this->_env;
	}
		
	function isDebugEnv() {
		$status = false;

		if (!empty($this->_env)) {
			$status = ENV_DEBUG;
			foreach ($this->_env['debug'] as $option)
				switch ($option) {
					case 'print':
						$status = $status | ENV_DEBUG_PRINTED;
						break;
					case 'limit':
						$status = $status | ENV_DEBUG_LIMITED;
						break;
					case 'test':
						$status = $status | ENV_DEBUG_TESTED;
						break;
					case 'import':
						$status = $status | ENV_DEBUG_IMPORT;
						break;
					case 'export':
						$status = $status | ENV_DEBUG_EXPORT;
						break;
					default:
						$status = $status | true;
						break;
				}
		}
	
		return $status;
	}
	
	function getMaxIterarions() {
		if ($this->isDebugEnv() & ENV_DEBUG_LIMITED)
			return intval($this->_env['limit']);
		return 0;
	}
	
	function isCLI() {
		return php_sapi_name() === 'cli';
	}

}
