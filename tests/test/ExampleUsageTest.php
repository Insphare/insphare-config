<?php

use Insphare\Base\ObjectContainer;
use Insphare\Config\Configuration;

/**
 * Class ExampleUsage
 */
class ExampleUsageTest extends PHPUnit_Framework_TestCase {

	/**
	 */
	public function testBootstrapUsageWithStaticCall() {
		$o = new ObjectContainer();
		$o->setConfiguration(new Configuration());

		$config = $o->getConfiguration();
		$config->clear();
		$config->setStaging(Configuration::ENVIRONMENT_DEV);
		$config->addOverwritePath(dirname(__DIR__) . '/configOverwrite');
		$config->addConfigPath(dirname(__DIR__) . '/config');

		$this->assertSame('2-overwrite', Configuration::g('memcache'));
	}

	public function testBootstrapUsageWithStaticCallCompare() {
		$o = new ObjectContainer();
		$o->setConfiguration(new Configuration());

		$config = $o->getConfiguration();
		$config->clear();
		$config->setStaging(Configuration::ENVIRONMENT_DEV);
		$config->addOverwritePath(dirname(__DIR__) . '/configOverwrite');
		$config->addConfigPath(dirname(__DIR__) . '/config');

		// 2-overwrite
		$this->assertSame(true, Configuration::gc('memcache')->eq('2-overwrite'));
		$this->assertSame(false, Configuration::gc('memcache')->isOnlyAlpha());
		$this->assertSame(true, Configuration::gc('memcache')->isString());
		$this->assertSame(false, Configuration::gc('memcache')->isTrue());
		$this->assertSame(false, Configuration::gc('memcache')->isFalse());
		$this->assertSame(true, Configuration::gc('memcache')->isNotNull());
		$this->assertSame(false, Configuration::gc('memcache')->isNull());
		$this->assertSame(true, Configuration::gc('integer')->isOnlyNumeric());
	}

	public function testBootstrapUsageWithObjectCallCompare() {
		$o = new ObjectContainer();
		$o->setConfiguration(new Configuration());

		/** @var Configuration $config */
		$config = $o->getConfiguration();
		$config->clear();
		$config->setStaging(Configuration::ENVIRONMENT_DEV);
		$config->addOverwritePath(dirname(__DIR__) . '/configOverwrite');
		$config->addConfigPath(dirname(__DIR__) . '/config');

		// 2-overwrite
		$this->assertSame(true, $config->getComparison('memcache')->eq('2-overwrite'));
		$this->assertSame(false, $config->getComparison('memcache')->isOnlyAlpha());
		$this->assertSame(true, $config->getComparison('memcache')->isString());
		$this->assertSame(false, $config->getComparison('memcache')->isTrue());
		$this->assertSame(false, $config->getComparison('memcache')->isFalse());
		$this->assertSame(true, $config->getComparison('memcache')->isNotNull());
		$this->assertSame(false, $config->getComparison('memcache')->isNull());
		$this->assertSame(true, $config->getComparison('integer')->isOnlyNumeric());
	}
}
