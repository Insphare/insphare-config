<?php
namespace Insphare\Config;

/**
 * Class Comparison
 * @package Insphare\Config
 */
class Comparison {
	/**
	 * @var mixed
	 */
	private $mixed;

	/**
	 * @param $mixed
	 */
	public function __construct($mixed) {
		$this->mixed = $mixed;
	}

	/**
	 * @return bool
	 */
	public function isTrue() {
		return true === $this->mixed;
	}

	/**
	 * @return bool
	 */
	public function isFalse() {
		return false === $this->mixed;
	}

	/**
	 * @return bool
	 */
	public function isEmpty() {
		return empty($this->mixed);
	}

	/**
	 * @return bool
	 */
	public function isArray() {
		return is_array($this->mixed);
	}

	/**
	 * @return bool
	 */
	public function isOnlyAlpha() {
		return ctype_alpha(is_scalar($this->mixed) && !is_bool($this->mixed) ? (string)$this->mixed : $this->mixed);
	}

	/**
	 * @return bool
	 */
	public function isOnlyNumeric() {
		return ctype_digit(is_scalar($this->mixed) && !is_bool($this->mixed) ? (string)$this->mixed : $this->mixed);
	}

	/**
	 * @return bool
	 */
	public function isAlphaNum() {
		return ctype_alnum(is_scalar($this->mixed) && !is_bool($this->mixed) ? (string)$this->mixed : $this->mixed);
	}

	/**
	 * @return bool
	 */
	public function isString() {
		return is_string($this->mixed);
	}

	/**
	 * @param $value
	 * @return bool
	 */
	public function eq($value) {
		return $this->mixed === $value;
	}

	/**
	 * @param $value
	 * @return bool
	 */
	public function neq($value) {
		return $this->mixed !== $value;
	}

	/**
	 * @param $value
	 * @return bool
	 */
	public function gt($value) {
		return $this->mixed > $value;
	}

	/**
	 * @param $value
	 * @return bool
	 */
	public function lt($value) {
		return $this->mixed < $value;
	}

	/**
	 * @param $value
	 * @return bool
	 */
	public function gte($value) {
		return $this->mixed >= $value;
	}

	/**
	 * @param $value
	 * @return bool
	 */
	public function lte($value) {
		return $this->mixed <= $value;
	}

	/**
	 * @return bool
	 */
	public function isNull() {
		return is_null($this->mixed);
	}

	/**
	 * @return bool
	 */
	public function isNotNull() {
		return !is_null($this->mixed);
	}

	/**
	 * @return mixed
	 */
	public function debug() {
		return print_r($this->mixed, true);
	}
}
