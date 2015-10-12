<?php

require_once(implode(DIRECTORY_SEPARATOR, [__DIR__, "..", "src", "Nest.php"]));

use \Hambrook\Nest\Nest as Nest;

/**
 * Tests for PHPUnit
 *
 * @author     Rick Hambrook <rick@rickhambrook.com>
 * @copyright  2015 Rick Hambrook
 * @license    https://www.gnu.org/licenses/gpl.txt  GNU General Public License v3
 */
class objectStaticTest extends PHPUnit_Framework_TestCase {

	public function testCreate() {
		$data = new objectSetTestData;
		return $data;
	}

	/**
	 * @depends testCreate
	 */
	public function testGet($data) {
		// Valid
		$this->assertEquals("bar",     Nest::_get($data, "foo"));
		// Valid, with default
		$this->assertEquals("bar",     Nest::_get($data, "foo", "DEFAULT"));
		// Invalid, no default
		$this->assertEquals(null,      Nest::_get($data, "BAD"));
		// Invalid, with default
		$this->assertEquals("DEFAULT", Nest::_get($data, "BAD", "DEFAULT"));
	}

	/**
	 * @depends testCreate
	 */
	public function testGetNested($data) {
		// Valid
		$this->assertEquals("three",   Nest::_get($data, ["one", "two"]));
		// Valid, with default
		$this->assertEquals("three",   Nest::_get($data, ["one", "two"], "DEFAULT"));
		// Invalid first, no default
		$this->assertEquals(null,      Nest::_get($data, ["BAD", "two"]));
		// Invalid second, no default
		$this->assertEquals(null,      Nest::_get($data, ["one", "BAD"]));
		// Invalid first, with default
		$this->assertEquals("DEFAULT", Nest::_get($data, ["BAD", "two"], "DEFAULT"));
		// Invalid second, with default
		$this->assertEquals("DEFAULT", Nest::_get($data, ["one", "BAD"], "DEFAULT"));
	}

	/**
	 * @depends testCreate
	 */
	public function testSet($data) {
		// Valid
		$this->assertEquals("newbar",  Nest::_set($data, "foo", "newbar")->foo);
		// Invalid, no default
		$this->assertEquals("newfoo",  Nest::_set($data, "foo2", "newfoo")->foo2);
	}

	/**
	 * @depends testCreate
	 */
	public function testSetNested($data) {
		// Valid
		$this->assertEquals("four",    Nest::_set($data, ["one", "two"], "four")->one["two"]);
		// Valid, with default
		$this->assertEquals("newtwo",  Nest::_set($data, ["one", "two2"], "newtwo")->one["two2"]);
	}

}

class objectStaticTestData {
	public $foo = "bar";
	public $one = [
		"two" => "three"
	];
}