<?php

/**
 * Tests for PHPUnit
 *
 * @author     Rick Hambrook <rick@rickhambrook.com>
 * @copyright  2015 Rick Hambrook
 * @license    https://www.gnu.org/licenses/gpl.txt  GNU General Public License v3
 */

require_once(implode(DIRECTORY_SEPARATOR, [__DIR__, "..", "src", "Nest.php"]));

use \Hambrook\Nest as Nest;

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
		$this->assertEquals(Nest::_get($data, "foo"), "bar");
		// Valid, with default
		$this->assertEquals(Nest::_get($data, "foo", "DEFAULT"), "bar");
		// Invalid, no default
		$this->assertEquals(Nest::_get($data, "BAD"), null);
		// Invalid, with default
		$this->assertEquals(Nest::_get($data, "BAD", "DEFAULT"), "DEFAULT");
	}

	/**
	 * @depends testCreate
	 */
	public function testGetNested($data) {
		// Valid
		$this->assertEquals(Nest::_get($data, ["one", "two"]), "three");
		// Valid, with default
		$this->assertEquals(Nest::_get($data, ["one", "two"], "DEFAULT"), "three");
		// Invalid first, no default
		$this->assertEquals(Nest::_get($data, ["BAD", "two"]), null);
		// Invalid second, no default
		$this->assertEquals(Nest::_get($data, ["one", "BAD"]), null);
		// Invalid first, with default
		$this->assertEquals(Nest::_get($data, ["BAD", "two"], "DEFAULT"), "DEFAULT");
		// Invalid second, with default
		$this->assertEquals(Nest::_get($data, ["one", "BAD"], "DEFAULT"), "DEFAULT");
	}

	/**
	 * @depends testCreate
	 */
	public function testSet($data) {
		// Valid
		$this->assertEquals(Nest::_set($data, "foo", "newbar")->foo, "newbar");
		// Invalid, no default
		$this->assertEquals(Nest::_set($data, "foo2", "newfoo")->foo2, "newfoo");
	}

	/**
	 * @depends testCreate
	 */
	public function testSetNested($data) {
		// Valid
		$this->assertEquals(Nest::_set($data, ["one", "two"], "four")->one["two"], "four");
		// Valid, with default
		$this->assertEquals(Nest::_set($data, ["one", "two2"], "newtwo")->one["two2"], "newtwo");
	}

}

class objectStaticTestData {
	public $foo = "bar";
	public $one = [
		"two" => "three"
	];
}