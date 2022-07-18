<?php
namespace Insphare\Config;

use Symfony\Component\Yaml\Yaml;

/**
 * Class Parser
 *
 * @package Insphare\Config
 */
class Parser {

	/**
	 * @var array
	 */
	private $config = [];

	/**
	 * @var null
	 */
	private $useFileNameAsKey = null;

	/**
	 * @var string
	 */
	private $usedStaging = '';

	/**
	 * @param array $config
	 */
	public function __construct(array &$config) {
		$this->config = & $config;
	}

	/**
	 * @param string $usedStaging
	 */
	public function setUsedStaging($usedStaging) {
		$this->usedStaging = (string)$usedStaging;
	}

	/**
	 * @param null $useFileNameAsKey
	 */
	public function setUseFileNameAsKey($useFileNameAsKey) {
		$this->useFileNameAsKey = (bool)$useFileNameAsKey;
	}

	/**
	 * @param array $files
	 */
	public function directory(array $files) {
		$config = array();

		/** @var \SplFileInfo $splFile */
		foreach ($files as $splFile) {
			$parsed = (array)Yaml::parse(file_get_contents((string)$splFile));
			$this->clearNotNeededEnvironments($parsed);

			// overwrite the environment
			if (isset($parsed['environment-' . $this->usedStaging])) {
				$parsed = array_replace_recursive($parsed, $parsed['environment-' . $this->usedStaging]);
			}
			unset($parsed['environment-' . $this->usedStaging]);

			// check if filename as key desired
			if (true === $this->useFileNameAsKey) {
				$config[strtolower($splFile->getBasename('.yml'))] = $parsed;
			}
			else {
				$config = $parsed;
			}

			// write into config
			$this->config = array_replace_recursive($this->config, (array)$config);
		}
	}

	/**
	 * Remove not needed items for another environments.
	 *
	 * @param array $config
	 */
	private function clearNotNeededEnvironments(array &$config) {
		$envPrefix = 'environment-';

		foreach (array_keys($config) as $key) {
			// remove not used staging config.
			if ($envPrefix === strtolower(substr($key, 0, strlen($envPrefix))) && strtolower($key) != $envPrefix . $this->usedStaging) {
				unset($config[$key]);
			}
		}
	}
}
