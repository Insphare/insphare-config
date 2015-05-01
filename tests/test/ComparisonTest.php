<?php
use Insphare\Config\Comparison;

/**
 * Class ComparisonTest
 */
class ComparisonTest extends PHPUnit_Framework_TestCase {

	/**
	 * @param $value
	 * @return Comparison
	 */
	public function getComparison($value) {
		return new Comparison($value);
	}

	/**
	 * @dataProvider providerBooleanTrue
	 */
	public function testTrue($value, $expectedValue) {
		$this->assertSame($expectedValue, $this->getComparison($value)->isTrue());
	}

	/**
	 * @return array
	 */
	public function providerBooleanTrue() {
		return array(
			array(true, true),
			array(false, false),
			array(null, false),
			array(1, false),
			array(0, false),
		);
	}

	/**
	 * @dataProvider providerBooleanFalse
	 */
	public function testFalse($value, $expectedValue) {
		$this->assertSame($expectedValue, $this->getComparison($value)->isFalse());
	}

	/**
	 * @return array
	 */
	public function providerBooleanFalse() {
		return array(
			array(true, false),
			array(false, true),
			array(null, false),
			array(1, false),
			array(0, false),
		);
	}

	/**
	 * @dataProvider providerEmpty
	 */
	public function testEmpty($value, $expectedValue) {
		$this->assertSame($expectedValue, $this->getComparison($value)->isEmpty());
	}

	/**
	 * @return array
	 */
	public function providerEmpty() {
		return array(
			array(true, false),
			array(false, true),
			array(null, true),
			array(1, false),
			array(0, true),
			array(array(), true),
			array(array(1), false),
		);
	}

	/**
	 * @dataProvider providerArray
	 */
	public function testIsArray($value, $expectedValue) {
		$this->assertSame($expectedValue, $this->getComparison($value)->isArray());
	}

	/**
	 * @return array
	 */
	public function providerArray() {
		return array(
			array(true, false),
			array(false, false),
			array(null, false),
			array(1, false),
			array(0, false),
			array(array(), true),
			array(array(1), true),
			array([1], true),
			array([], true),
		);
	}

	/**
	 * @dataProvider providerString
	 */
	public function testIsOnlyAlpha($value, $expectedValue) {
		$this->assertSame($expectedValue, $this->getComparison($value)->isOnlyAlpha());
	}

	/**
	 * @return array
	 */
	public function providerString() {
		return array(
			array(true, false),
			array(false, false),
			array(null, false),
			array(1, false),
			array(0, false),
			array(array(), false),
			array(array(1), false),
			array([1], false),
			array([], false),
			array('', false),
			array('a', true),
			array('1', false),
		);
	}

	/**
	 * @dataProvider providerAlNum
	 */
	public function testIsAlNum($value, $expectedValue) {
		$this->assertSame($expectedValue, $this->getComparison($value)->isAlphaNum());
	}

	public function providerAlNum() {
		return array(
			array(true, false),
			array(false, false),
			array(null, false),
			array(1, true),
			array(0, true),
			array(array(), false),
			array(array(1), false),
			array([1], false),
			array([], false),
			array('', false),
			array('a', true),
			array('1', true),
			array('ab', true),
			array('ab343', true),
			array('ab3-43', false),
		);
	}
	/**
	 * @dataProvider providerIsString
	 */
	public function testIsString($value, $expectedValue) {
		$this->assertSame($expectedValue, $this->getComparison($value)->isString());
	}

	public function providerIsString() {
		return array(
			array(true, false),
			array(false, false),
			array(null, false),
			array(1, false),
			array(0, false),
			array(array(), false),
			array(array(1), false),
			array([1], false),
			array([], false),
			array('', true),
			array('a', true),
			array('1', true),
			array('ab', true),
			array('ab343', true),
			array('ab3-43', true),
		);
	}

	/**
	 * @dataProvider providerNumeric
	 */
	public function testIsNumeric($value, $expectedValue) {
		$this->assertSame($expectedValue, $this->getComparison($value)->isOnlyNumeric());
	}

	/**
	 * @return array
	 */
	public function providerNumeric() {
		return array(
			array(true, false),
			array(false, false),
			array(null, false),
			array(1, true),
			array(0, true),
			array(array(), false),
			array(array(1), false),
			array([1], false),
			array([], false),
			array('', false),
			array('a', false),
			array('11.assf', false),
			array('1', true),
		);
	}

	/**
	 * @dataProvider providerEq
	 */
	public function testEq($value, $comparisonValue, $expectedValue) {
		$this->assertSame($expectedValue, $this->getComparison($value)->eq($comparisonValue));
	}

	/**
	 * @dataProvider providerEq
	 */
	public function testNeq($value, $comparisonValue, $expectedValue) {
		$this->assertSame(!$expectedValue, $this->getComparison($value)->neq($comparisonValue));
	}

	/**
	 * @return array
	 */
	public function providerEq() {
		return array(
			array([], [], true),
			array('', '', true),
			array('a', 'a', true),
			array('11.assf', 'dd', false),
			array('1', 0, false),
			array('1', '1', true),
			array(0, 0, true)
		);
	}

	/**
	 * @dataProvider providerGt
	 */
	public function testGt($value, $comparisonValue, $expectedValue) {
		$this->assertSame($expectedValue, $this->getComparison($value)->gt($comparisonValue));
	}

	/**
	 * @return array
	 */
	public function providerGt() {
		return array(
			array([], [], false),
			array('', '', false),
			array('a', 'a', false),
			array('11.assf', 'dd', false),
			array('1', 0, true),
			array('1', '1', false),
			array('1', '2', false),
			array('2', '1', true),
			array(1, 1, false),
			array(1, 2, false),
			array(2, 1, true),
			array(0, 0, false)
		);
	}

	/**
	 * @dataProvider providerLt
	 */
	public function testLt($value, $comparisonValue, $expectedValue) {
		$this->assertSame($expectedValue, $this->getComparison($value)->lt($comparisonValue));
	}

	/**
	 * @return array
	 */
	public function providerLt() {
		return array(
			array([], [], false),
			array('', '', false),
			array('a', 'a', false),
			array('11.assf', 'dd', true),
			array('1', 0, false),
			array('1', '1', false),
			array('1', '2', true),
			array('2', '1', false),
			array(1, 1, false),
			array(1, 2, true),
			array(2, 1, false),
			array(0, 0, false)
		);
	}

	/**
	 * @dataProvider providerLte
	 */
	public function testLte($value, $comparisonValue, $expectedValue) {
		$this->assertSame($expectedValue, $this->getComparison($value)->lte($comparisonValue));
	}

	/**
	 * @return array
	 */
	public function providerLte() {
		return array(
			array('a', 'a', true),
			array('11.assf', 'dd', true),
			array('1', 0, false),
			array('1', '1', true),
			array('1', '2', true),
			array('2', '1', false),
			array(1, 1, true),
			array(1, 2, true),
			array(2, 1, false),
			array(0, 0, true),
			array(new DateTime(), new DateTime('2000-11-11'), false),
			array(new DateTime('2000-11-11'), new DateTime('2000-11-11'), true),
		);
	}

	/**
	 * @dataProvider providerGte
	 */
	public function testGte($value, $comparisonValue, $expectedValue) {
		$this->assertSame($expectedValue, $this->getComparison($value)->gte($comparisonValue));
	}

	/**
	 * @return array
	 */
	public function providerGte() {
		return array(
			array('a', 'a', true),
			array('11.assf', 'dddddddddddddd', false),
			array('1', 0, true),
			array('1', '1', true),
			array('1', '2', false),
			array('2', '1', true),
			array(1, 1, true),
			array(1, 2, false),
			array(2, 1, true),
			array(0, 0, true),
			array(new DateTime(), new DateTime('2000-11-11'), true),
			array(new DateTime('2000-11-11'), new DateTime('2000-11-11'), true),
		);
	}

	/**
	 * @dataProvider providerNull
	 */
	public function testIsNul($value, $expectedValue) {
		$this->assertSame($expectedValue, $this->getComparison($value)->isNull());
	}

	/**
	 * @dataProvider providerNull
	 */
	public function testIsNotNul($value, $expectedValue) {
		$this->assertSame(!$expectedValue, $this->getComparison($value)->isNotNull());
	}

	/**
	 * @return array
	 */
	public function providerNull() {
		return array(
			array(1, false),
			array(true, false),
			array(null, true),
			array('null', false),
		);
	}
}
