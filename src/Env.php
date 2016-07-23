<?php

	//define('NEW_LINE', '<br />');
	define('NEW_LINE', "<br />\r\n");
	
	define("ENV_DEBUG", 1 << 8);
	define("ENV_DEBUG_PRINTED", 1);
	define("ENV_DEBUG_LIMITED", 1 << 1);
	define("ENV_DEBUG_TESTED", 1 << 2);
	define("ENV_DEBUG_IMPORT", 1 << 3);
	define("ENV_DEBUG_EXPORT", 1 << 4);
	
	define("ENV_LOCAL", 1 << 9);
	define("ENV_OS_MAC", 1);
	define("ENV_OS_LINUX", 1 << 1);
	define("ENV_OS_WIN", 1 << 2);

	define("ENV_AMAZON", 1 << 10);
	define("ENV_GOOGLE", 1 << 11);
	define("ENV_PROD_API", 1);
	define("ENV_PROD_DEV", 1 << 1);
	define("ENV_PROD_CRAWL", 1 << 2);
	
	
	
	function getEnvRunning() {
		
		$hostname = gethostname();
		switch ($hostname) {
			case 'api.shopnfly.com' :
				return ENV_GOOGLE | ENV_PROD_API;
			case 'api3.shopnfly.com' :
				return ENV_GOOGLE | ENV_PROD_DEV;
			case 'crawl.shopnfly.com' :
				return ENV_GOOGLE | ENV_CRAWL;
			case 'ip-10-97-27-41' :
				return ENV_AMAZON | ENV_PROD_API;
			case 'dev.shopnfly.com' :
				return ENV_AMAZON | ENV_PROD_DEV;
			case 'Eylon-Steiners-MacBook-Pro.local' :
				return ENV_LOCAL | ENV_OS_MAC;
			case 'EylonsMacBookPro.local' :
				return ENV_LOCAL | ENV_OS_MAC;
			case 'zivlit-HP-ProBook-4530s' :
				return ENV_LOCAL | ENV_OS_LINUX;
			case 'zivshopnfly' :
				return ENV_LOCAL | ENV_OS_LINUX;
			default :
				return 0;
		}
	}
	
	function getEnvUrl() {
		$host = getEnvRunning();
		if ($host & ENV_LOCAL) {
			if ($host & ENV_OS_MAC)
				return 'localhost/df/public';
			else if ($host & ENV_OS_LINUX)
				return 'localhost/shopnfly_server/public';
		} else {
			if ($host & ENV_PROD_API)
				return 'api.shopnfly.com';
			else if ($host & ENV_PROD_DEV)
				return 'api3.shopnfly.com';
		}
	}

	function getWebUrl() {
		$host = getEnvRunning();
		if ($host & ENV_PROD_API)
			return 'https://www.shopnfly.com';
		else if ($host & ENV_PROD_DEV)
			return 'https://web3.shopnfly.com';
		else return 'https://https://www.shopnfly.com';
	}
	
	function getEnvEmail() {
		$env = getEnvRunning();
		if ($env & ENV_LOCAL) {
			if (stripos(gethostname(), 'ziv'))
				return "ziv@shopnfly.com";
			if (stripos(gethostname(), 'eylon'))
				return "eylon@shopnfly.com";
		} else return 'system@shopnfly.com';
	}
	
	function getEnvName() {
		$env = getEnvRunning();
		if ($env & ENV_LOCAL)
			return 'localhost';
		else if ($env & ENV_PROD_DEV)
			return 'dev';
		else if ($env & ENV_PROD_API)
			return 'api';
	}
		
	function isDebugEnv() {
		$status = false;

		if (isset($_REQUEST['debug']) && !empty($_REQUEST['debug'])) {
			$optionsArr = explode(',', $_REQUEST['debug']);

			if (!empty($optionsArr)) {
				$status = ENV_DEBUG;
				foreach ($optionsArr as $option)
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
		}

		return $status;
	}

	function getMaxIterarions() {
		if (isDebugEnv() & ENV_DEBUG_LIMITED)
			return intval($_REQUEST['limit']);
		return null;
	}
	
	function showInDebugEnv($var) {
	
		if (isset($_REQUEST['debug']) && ($_REQUEST['debug'] == 'true')) {
			new dBug($var);
		}

		if (php_sapi_name() === 'cli') {
			print_r($var);
			echo PHP_EOL;
		}
	}

?>