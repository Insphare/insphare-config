<?php

use Insphare\Base\Exception;
use Insphare\Config\Configuration;

/**
 * Class ParseTest
 */
class ParseTest extends PHPUnit_Framework_TestCase {

	/**
	 */
	public function testParseConfig() {
		$expected = [
			'host' => 'localhost',
			'dbname' => 'default_databasename',
			'user' => 'default_username',
			'password' => 'default_password',
			'port' => '3306',
			'driver' => 'pdo_mysql',
			'charset' => 'UTF8',
		];

		$config = new Configuration();
		$config->clear();
		$config->setStaging(Configuration::ENVIRONMENT_DEFAULT);
		$config->addConfigPath(dirname(__DIR__) . '/config');
		$cfg = $config->get('database-credentials');
		$this->assertSame($expected, $cfg);
	}

	/**
	 */
	public function testParseConfigWithFileAsKey() {
		$expected = [
			'database-credentials' => [
				'host' => 'localhost',
				'dbname' => 'default_databasename',
				'user' => 'default_username',
				'password' => 'default_password',
				'port' => '3306',
				'driver' => 'pdo_mysql',
				'charset' => 'UTF8',
			]
		];

		$config = new Configuration();
		$config->clear();
		$config->setStaging(Configuration::ENVIRONMENT_DEFAULT);
		$config->addConfigPath(dirname(__DIR__) . '/config');
		$config->setUseFileNameAsKey(true);
		$cfg = $config->get('database');
		$this->assertSame($expected, $cfg);
		$this->assertSame('3306', $config->walk('database.database-credentials.port'));
	}

	/**
	 * @dataProvider overwriteProvider
	 * @param $environment
	 * @param $expectedValue
	 */
	public function testParseConfigEnv($environment, $expectedValue) {
		$config = new Configuration();
		$config->clear();
		$config->setStaging($environment);
		$config->addConfigPath(dirname(__DIR__) . '/config');
		$cfg = $config->get('memcache');
		$this->assertSame($expectedValue, $cfg);
	}

	/**
	 * @return array
	 */
	public function overwriteProvider() {
		return array(
			array(Configuration::ENVIRONMENT_DEFAULT, 1),
			array(Configuration::ENVIRONMENT_DEV, 2),
			array(Configuration::ENVIRONMENT_ALPHA, 3),
			array(Configuration::ENVIRONMENT_BETA, 4),
			array(Configuration::ENVIRONMENT_TESTING, 5),
			array(Configuration::ENVIRONMENT_STABLE, 6),
		);
	}

	/**
	 * @dataProvider overwriteProvider
	 *
	 * @param $environment
	 * @param $expectedValue
	 */
	public function testOverWriteDirectories($environment, $expectedValue) {
		$config = new Configuration();
		$config->clear();
		$config->setStaging($environment);
		$config->addOverwritePath(dirname(__DIR__) . '/configOverwrite');
		$config->addConfigPath(dirname(__DIR__) . '/config');
		$cfg = $config->get('memcache');
		$this->assertSame($expectedValue . '-overwrite', $cfg);
	}

	/**
	 * @dataProvider overwriteProvider
	 *
	 * @param $environment
	 * @param $expectedValue
	 */
	public function testOverWriteDirectoriesWithIdentifierDotHidden($environment, $expectedValue) {
		$config = new Configuration();
		$config->clear();
		$config->setStaging($environment);
		$config->addConfigPath(dirname(__DIR__) . '/configWithSubFolder');
		$config->addOverwritePath(dirname(__DIR__) . '/configWithSubFolder/___hiddenFolderTwo');
		$config->setOverwriteSubPathIdentifier('\.hidden');
		$cfg = $config->get('memcache');
		$this->assertSame('.hidden-' . $expectedValue, $cfg);
	}

	/**
	 * @dataProvider overwriteProvider
	 *
	 * @param $environment
	 * @param $expectedValue
	 */
	public function testOverWriteDirectoriesWithIdentifierUnderScoreHidden($environment, $expectedValue) {
		$config = new Configuration();
		$config->clear();
		$config->setStaging($environment);
		$config->addConfigPath(dirname(__DIR__) . '/configWithSubFolder');
		$config->addOverwritePath(dirname(__DIR__) . '/configWithSubFolder/.hiddenFolderWithoutVCS');
		$config->setOverwriteSubPathIdentifier('___hidden');
		$cfg = $config->get('memcache');
		$this->assertSame('__hidden-' . $expectedValue, $cfg);
	}

	/**
	 *
	 */
	public function testOwnImplementedEnvironment() {
		$gamma = 'gamma';
		$config = new Configuration();
		$config->clear();
		$config->addEnvironment($gamma);
		$config->setStaging($gamma);
		$config->addOverwritePath(dirname(__DIR__) . '/configOverwrite');
		$config->addConfigPath(dirname(__DIR__) . '/config');
		$cfg = $config->get('memcache');
		$this->assertSame($gamma . '-overwrite', $cfg);
	}

	/**
	 * Ausgangssituration:
	 * - Developement-Environment
	 * - File-Default: Werte ohne Überschreibung
	 * - File-Overwerite: Selbes File aber ohne "master-config" sondern nur Übrschreibungen.
	 */
	public function testHeredityTest() {
		$config = new Configuration();
		$config->clear();
		$config->setStaging(Configuration::ENVIRONMENT_DEV);
		$config->addConfigPath(dirname(__DIR__) . '/config-heredity-main');
		$config->addConfigPath(dirname(__DIR__) . '/config-heredity-project-overwrite');
		$this->assertSame('dev-route', $config->get('http.freeze.redirect'));
		$this->assertSame('other-freeze-main', $config->get('http.other.freeze'));
	}

	public function testHeredityTestOtherStage() {
		$config = new Configuration();
		$config->clear();
		$config->setStaging(Configuration::ENVIRONMENT_TESTING);
		$config->addConfigPath(dirname(__DIR__) . '/config-heredity-main');
		$config->addConfigPath(dirname(__DIR__) . '/config-heredity-project-overwrite');
		$this->assertSame('lom-route', $config->get('http.freeze.redirect'));
	}
}
