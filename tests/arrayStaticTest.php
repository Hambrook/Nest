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

class arrayStaticTest extends PHPUnit_Framework_TestCase {

	private $data = [
		"foo" => "bar",
		"one" => [
			"two" => "three"
		]
	];

	public function testGet() {
		// Valid
		$this->assertEquals(Nest::_get($this->data, "foo"), "bar");
		// Valid, with default
		$this->assertEquals(Nest::_get($this->data, "foo", "DEFAULT"), "bar");
		// Invalid, no default
		$this->assertEquals(Nest::_get($this->data, "BAD"), null);
		// Invalid, with default
		$this->assertEquals(Nest::_get($this->data, "BAD", "DEFAULT"), "DEFAULT");
	}

	public function testGetNested() {
		// Valid
		$this->assertEquals(Nest::_get($this->data, ["one", "two"]), "three");
		// Valid, with default
		$this->assertEquals(Nest::_get($this->data, ["one", "two"], "DEFAULT"), "three");
		// Invalid first, no default
		$this->assertEquals(Nest::_get($this->data, ["BAD", "two"]), null);
		// Invalid second, no default
		$this->assertEquals(Nest::_get($this->data, ["one", "BAD"]), null);
		// Invalid first, with default
		$this->assertEquals(Nest::_get($this->data, ["BAD", "two"], "DEFAULT"), "DEFAULT");
		// Invalid second, with default
		$this->assertEquals(Nest::_get($this->data, ["one", "BAD"], "DEFAULT"), "DEFAULT");
	}

	public function testSet() {
		// Valid
		$this->assertEquals(Nest::_set($this->data, "foo", "newbar")["foo"], "newbar");
		// Invalid, no default
		$this->assertEquals(Nest::_set($this->data, "foo2", "newfoo")["foo2"], "newfoo");
	}

	public function testSetNested() {
		// Valid
		$this->assertEquals(Nest::_set($this->data, ["one", "two"], "four")["one"]["two"], "four");
		// Valid, with default
		$this->assertEquals(Nest::_set($this->data, ["one", "two2"], "newtwo")["one"]["two2"], "newtwo");
	}

}