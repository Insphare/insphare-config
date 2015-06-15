<?php
namespace Insphare\Config;

use Insphare\Base\Exception;
use Insphare\Base\DirectoryIterator;
use Insphare\Base\ObjectContainer;

class Configuration {

	const ENVIRONMENT_DEFAULT = 'default';
	const ENVIRONMENT_DEV = 'dev';
	const ENVIRONMENT_ALPHA = 'alpha';
	const ENVIRONMENT_BETA = 'beta';
	const ENVIRONMENT_TESTING = 'testing';
	const ENVIRONMENT_STABLE = 'stable';

	/**
	 * Contains the current staging environment.
	 *
	 * @var string
	 */
	private $staging = self::ENVIRONMENT_DEFAULT;

	/**
	 * Contains the complete configuration.
	 *
	 * @var array
	 */
	private static $config = [];

	/**
	 * Contains configuration directories.
	 *
	 * @var array
	 */
	private $configPath = [];

	/**
	 * Contains configuration directories to overwrite the config.
	 *
	 * @var array
	 */
	private $overwritePath = [];

	/**
	 * Collected config files which are to parse.
	 *
	 * @var array
	 */
	private $defaultFiles = [];

	/**
	 * Collected config overwrite files which are to parse.
	 *
	 * @var array
	 */
	private $overwriteFiles = [];

	/**
	 * RegEx to identify a directory to overwrite.
	 *
	 * @var string
	 */
	private $overWriteRegExIdentifier = '\.hidden';

	/**
	 * @var bool
	 */
	private $useFileNameAsKey = false;

	/**
	 * @var Parser
	 */
	private $parser;

	/**
	 * Separator char to walk the array.
	 *
	 * @var string
	 */
	private $walkDelimiter = '.';

	/**
	 * @var array
	 */
	private $knownEnvironment = [
		self::ENVIRONMENT_DEFAULT,
		self::ENVIRONMENT_DEV,
		self::ENVIRONMENT_ALPHA,
		self::ENVIRONMENT_BETA,
		self::ENVIRONMENT_DEV,
		self::ENVIRONMENT_TESTING,
		self::ENVIRONMENT_STABLE,
	];

	/**
	 *
	 */
	public function __construct() {
		$this->parser = new Parser(self::$config);
	}

	/**
	 * @param string $environment
	 */
	public function addEnvironment($environment) {
		$this->knownEnvironment[(string)$environment] = (string)$environment;
	}

	/**
	 * Clears the config.
	 */
	public function clear() {
		self::c();
	}

	/**
	 *
	 */
	public function c() {
		self::$config = [];
	}

	/**
	 * Char to get off in array.
	 *
	 * @param string $delimiter
	 */
	public function setWalkDelimiter($delimiter) {
		$this->walkDelimiter = (string)$delimiter;
	}

	/**
	 * @param string $stage
	 * @throws Exception
	 */
	public function setStaging($stage) {
		if (!in_array((string)$stage, $this->knownEnvironment)) {
			throw new Exception('Invalid environment: ' . $stage);
		}

		$this->staging = (string)$stage;
	}

	/**
	 * Cleans the path with correct directory separator.
	 *
	 * @param string $path
	 */
	private function sanitizePath(&$path) {
		$path = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $path);
	}

	/**
	 * Adds a config directory.
	 *
	 * @param string $path
	 */
	public function addConfigPath($path) {
		$this->sanitizePath($path);
		$this->configPath[md5($path)] = $path;
	}

	/**
	 * Adds the path to overwrite the configuration.
	 *
	 * @param string $path
	 */
	public function addOverwritePath($path) {
		$this->sanitizePath($path);
		$this->overwritePath[md5($path)] = $path;
	}

	/**
	 * Sets an regex to identify a overwrite directory.
	 *
	 * @param string $regEx
	 */
	public function setOverwriteSubPathIdentifier($regEx) {
		$this->overWriteRegExIdentifier = $regEx;
	}

	/**
	 * @param boolean $useFileNameAsKey
	 */
	public function setUseFileNameAsKey($useFileNameAsKey) {
		$this->useFileNameAsKey = (bool)$useFileNameAsKey;
	}

	/**
	 * @return boolean
	 */
	public function getUseFileNameAsKey() {
		return $this->useFileNameAsKey;
	}

	/**
	 * Parses files through directory recursively
	 *
	 * @param $directory
	 */
	protected function parseFiles($directory) {
		$directoryIterator = new DirectoryIterator($directory);
		$directoryIterator->setRecursive(true);
		$directoryIterator->addAllowedExtension('yml');

		foreach ($directoryIterator->getSplFiles() as $splFile) {
			if (null !== $this->overWriteRegExIdentifier && preg_match('~' . $this->overWriteRegExIdentifier . '~i', (string)$splFile)) {
				$this->overwriteFiles[] = $splFile;
			}
			else {
				$this->defaultFiles[] = $splFile;
			}
		}
	}

	/**
	 * Parses the config through the directories.
	 */
	private function parse() {
		if (!empty(self::$config)) {
			return;
		}

		$callback = array($this, 'parseFiles');
		array_walk($this->configPath, $callback);
		array_walk($this->overwritePath, $callback);

		$this->parser->setUsedStaging($this->staging);
		$this->parser->setUseFileNameAsKey($this->useFileNameAsKey);

		$this->parser->directory($this->defaultFiles);
		$this->parser->directory($this->overwriteFiles);
	}

	/**
	 * @param string $key
	 * @return null
	 */
	public function get($key) {
		$this->parse();
		return isset(self::$config[(string)$key]) ? self::$config[(string)$key] : null;
	}

	/**
	 * @param string $key
	 * @return Comparison
	 */
	public function getComparison($key) {
		return new Comparison($this->get($key));
	}

	/**
	 * @param string $key
	 * @return mixed
	 */
	public static function g($key) {
		$object = new ObjectContainer();
		return $object->getConfiguration()->get($key);
	}

	/**
	 * This should not to be use.
	 *
	 * @param $key
	 * @param $value
	 * @throws Exception
	 */
	public static function reWrite($key, $value) {
		if (!isset(self::$config[(string)$key])) {
			throw new Exception('Config key: "' . $key . '" does not exists. There can only be overwritten when the key already is given.');
		}

		self::$config[(string)$key] = $value;
	}

	/**
	 * @param string $key
	 * @return Comparison
	 */
	public static function gc($key) {
		return new Comparison(self::g($key));
	}

	/**
	 * Get the config by walking.
	 *
	 * @param string $path
	 * @return mixed
	 */
	public function walk($path) {
		$this->parse();

		$path = explode($this->walkDelimiter, $path);
		$config = self::$config;
		foreach ($path as $key) {
			if (!isset($config[$key])) {
				return null;
			}

			$config = $config[$key];
		}

		return $config;
	}
}
