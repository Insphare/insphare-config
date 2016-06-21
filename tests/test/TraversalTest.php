<?php

use Insphare\Base\ObjectContainer;
use Insphare\Config\Configuration;

class TraversalTest extends PHPUnit_Framework_TestCase {

	public function testBootstrapUsageWithObjectCallWalk() {
		$o = new ObjectContainer();
		$o->setConfiguration(new Configuration());

		/** @var Configuration $config */
		$config = $o->getConfiguration();
		$config->clear();
		$config->setStaging(Configuration::ENVIRONMENT_DEV);
		$config->addOverwritePath(dirname(__DIR__) . '/configOverwrite');
		$config->addConfigPath(dirname(__DIR__) . '/config');

		$this->assertSame('root-child_1', $config->walk('root.child_1'));
		$this->assertSame('real_root_child_1', $config->walk('root.child.1'));
		$this->assertSame('real_root_child_2', $config->walk('root.child.2'));
		$this->assertSame('overwritten', $config->walk('root.child.3'));
		$this->assertSame(true, $config->walk('root.child.4'));
		$this->assertSame(false, $config->walk('root.child.5'));
	}

	public function testBootstrapUsageStaticCallWalk() {
		$o = new ObjectContainer();
		$o->setConfiguration(new Configuration());

		/** @var Configuration $config */
		$config = $o->getConfiguration();
		$config->clear();
		$config->setStaging(Configuration::ENVIRONMENT_DEV);
		$config->addOverwritePath(dirname(__DIR__) . '/configOverwrite');
		$config->addConfigPath(dirname(__DIR__) . '/config');

		$this->assertSame('root-child_1', Configuration::w('root.child_1'));
		$this->assertSame('real_root_child_1', Configuration::W('root.child.1'));
		$this->assertSame('real_root_child_2', Configuration::w('root.child.2'));
		$this->assertSame('overwritten', Configuration::w('root.child.3'));
		$this->assertSame(true, Configuration::w('root.child.4'));
		$this->assertSame(false, Configuration::w('root.child.5'));
	}

	public function testBootstrapUsageWithObjectCallWalkCompare() {
		$o = new ObjectContainer();
		$o->setConfiguration(new Configuration());

		/** @var Configuration $config */
		$config = $o->getConfiguration();
		$config->clear();
		$config->setStaging(Configuration::ENVIRONMENT_DEV);
		$config->addOverwritePath(dirname(__DIR__) . '/configOverwrite');
		$config->addConfigPath(dirname(__DIR__) . '/config');

		$this->assertFalse($config->getComparisonWalk('root.child_1')->isEmpty());
		$this->assertFalse($config->getComparisonWalk('root.child.1')->isArray());
		$this->assertFalse($config->getComparisonWalk('root.child.2')->isOnlyAlpha());
		$this->assertTrue($config->getComparisonWalk('root.child.3')->isString());
		$this->assertTrue($config->getComparisonWalk('root.child.4')->isTrue());
		$this->assertFalse($config->getComparisonWalk('root.child.5')->isTrue());
	}

	public function testBootstrapUsageWithStaticCallWalkCompare() {
		$o = new ObjectContainer();
		$o->setConfiguration(new Configuration());

		/** @var Configuration $config */
		$config = $o->getConfiguration();
		$config->clear();
		$config->setStaging(Configuration::ENVIRONMENT_DEV);
		$config->addOverwritePath(dirname(__DIR__) . '/configOverwrite');
		$config->addConfigPath(dirname(__DIR__) . '/config');

		$this->assertFalse(Configuration::wc('root.child_1')->isEmpty());
		$this->assertFalse(Configuration::wc('root.child.1')->isArray());
		$this->assertFalse(Configuration::wc('root.child.2')->isOnlyAlpha());
		$this->assertTrue(Configuration::wc('root.child.3')->isString());
		$this->assertTrue(Configuration::wc('root.child.4')->isTrue());
		$this->assertFalse(Configuration::wc('root.child.5')->isTrue());
	}

}
