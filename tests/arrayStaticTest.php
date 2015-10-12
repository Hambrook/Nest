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
class arrayStaticTest extends PHPUnit_Framework_TestCase {

	private $data = [
		"foo" => "bar",
		"one" => [
			"two" => "three"
		]
	];

	public function testGet() {
		// Valid
		$this->assertEquals("bar",     Nest::_get($this->data, "foo"));
		// Valid, with default
		$this->assertEquals("bar",     Nest::_get($this->data, "foo", "DEFAULT"));
		// Invalid, no default
		$this->assertEquals(null,      Nest::_get($this->data, "BAD"));
		// Invalid, with default
		$this->assertEquals("DEFAULT", Nest::_get($this->data, "BAD", "DEFAULT"));
	}

	public function testGetNested() {
		// Valid
		$this->assertEquals("three",   Nest::_get($this->data, ["one", "two"]));
		// Valid, with default
		$this->assertEquals("three",   Nest::_get($this->data, ["one", "two"], "DEFAULT"));
		// Invalid first, no default
		$this->assertEquals(null,      Nest::_get($this->data, ["BAD", "two"]));
		// Invalid second, no default
		$this->assertEquals(null,      Nest::_get($this->data, ["one", "BAD"]));
		// Invalid first, with default
		$this->assertEquals("DEFAULT", Nest::_get($this->data, ["BAD", "two"], "DEFAULT"));
		// Invalid second, with default
		$this->assertEquals("DEFAULT", Nest::_get($this->data, ["one", "BAD"], "DEFAULT"));
	}

	public function testSet() {
		// Valid
		$this->assertEquals("newbar",   Nest::_set($this->data, "foo", "newbar")["foo"]);
		// Invalid, no default
		$this->assertEquals("newfoo",   Nest::_set($this->data, "foo2", "newfoo")["foo2"]);
	}

	public function testSetNested() {
		// Valid
		$this->assertEquals("four",     Nest::_set($this->data, ["one", "two"], "four")["one"]["two"]);
		// Valid, with default
		$this->assertEquals("newtwo",   Nest::_set($this->data, ["one", "two2"], "newtwo")["one"]["two2"]);
	}

}