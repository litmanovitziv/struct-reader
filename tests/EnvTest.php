<?php

namespace tests;

set_include_path("./../". PATH_SEPARATOR . ini_get("include_path"));

require_once 'vendor/autoload.php';
require_once 'src/Env.php';

use DataPipeline\Env;

class EnvTest extends \PHPUnit_Framework_TestCase {

	public function testEmptyEnv() {
		$test = new Env();
	    $this->assertEmpty($test->getEnv());
	}

	public function testIsDebugEnv() {
		$env = array("debug" => "test,print");
		$this->assertEquals(ENV_DEBUG | ENV_DEBUG_TESTED | ENV_DEBUG_PRINTED, (new Env($env))->isDebugEnv());
		$this->assertFalse((new Env())->isDebugEnv());
	}

}
