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
class unsetTest extends PHPUnit_Framework_TestCase {

	private $data = [
		"foo" => "bar",
		"one" => [
			"two" => "three"
		]
	];

	public function testDelete() {
		$Nest = new Nest();
		$Nest->data($this->data);

		// First level
		$this->assertEquals(false,     $Nest->delete("foo")->exists("foo"));
		// Nested
		$this->assertEquals(false,     $Nest->delete(["one", "two"])->exists(["one", "two"]));
		// Make sure it only deleted the final level
		$this->assertEquals(true,      $Nest->exists(["one"]));

		$Nest = new Nest();
		$Nest->data($this->data);

		// Invalid, nested
		$this->assertEquals(false,     $Nest->delete(["BAD", "two"])->exists(["BAD", "two"]));
		// Invalid, nested
		$this->assertEquals(false,     $Nest->delete(["one", "BAD"])->exists(["one", "BAD"]));
		// Make sure it only deleted the final level
		$this->assertEquals(true,      $Nest->exists(["one"]));
		// Invalid, first level
		$this->assertEquals(false,     $Nest->delete("BAD")->exists("BAD"));
	}

	public function testUnset() {
		$Nest = new Nest();
		$Nest->data($this->data);

		// First level
		unset($Nest->foo);
		$this->assertEquals(false,     $Nest->exists("foo"));
		// Nested
		unset($Nest->one__two);
		$this->assertEquals(false,     $Nest->exists(["one", "two"]));
		// Make sure it only deleted the final level
		$this->assertEquals(true,      $Nest->exists("one"));

		$Nest = new Nest();
		$Nest->data($this->data);

		// Invalid, nested
		unset($Nest->BAD__two);
		$this->assertEquals(false,     $Nest->exists(["BAD", "two"]));
		// Invalid, nested
		unset($Nest->one__BAD);
		$this->assertEquals(false,     $Nest->exists(["one", "BAD"]));
		// Make sure it only deleted the final level
		$this->assertEquals(true,      $Nest->exists("one"));
		// Invalid, first level
		unset($Nest->BAD);
		$this->assertEquals(false,     $Nest->exists("BAD"));
	}

}