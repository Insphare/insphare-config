<?php

use Insphare\Base\Exception;
use Insphare\Config\Configuration;

/**
 * Class StagingTest
 */
class StagingTest extends PHPUnit_Framework_TestCase {

	/**
	 * @dataProvider stageProvider
	 */
	public function testSetRightStaging($staging) {
		$config = new Configuration();
		$config->setStaging($staging);
	}

	/**
	 * @return array
	 */
	public function stageProvider() {
		$environments = [
			[Configuration::ENVIRONMENT_DEFAULT],
			[Configuration::ENVIRONMENT_DEV],
			[Configuration::ENVIRONMENT_ALPHA],
			[Configuration::ENVIRONMENT_BETA],
			[Configuration::ENVIRONMENT_TESTING],
			[Configuration::ENVIRONMENT_STABLE],
		];

		return $environments;
	}

	/**
	 * @expectedException     Exception
	 * @dataProvider stageProvider
	 */
	public function testUnknownEnvironment() {
		$config = new Configuration();
		$config->setStaging('asdfasf');
	}

	/**
	 * @return array
	 */
	public function wrongStageProvider() {
		$environments = [
			["Irgendwas"],
			[null],
			[0],
			[],
		];

		return $environments;
	}

	/**
	 *
	 */
	public function testOwnStagingTest() {
		$gamma = 'gamma';
		$config = new Configuration();
		$config->addEnvironment($gamma);
		$config->setStaging($gamma);
	}
}
