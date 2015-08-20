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

class issetTest extends PHPUnit_Framework_TestCase {

	private $data = [
		"foo" => "bar",
		"one" => [
			"two" => "three"
		]
	];

	public function testExists() {
		$Nest = new Nest();
		// Valid
		$Nest->data($this->data);
		// First level
		$this->assertEquals(true,      $Nest->exists("foo"));
		// Nested
		$this->assertEquals(true,      $Nest->exists(["one", "two"]));

		// Invalid, first level
		$this->assertEquals(false,     $Nest->exists("BAD"));
		// Invalid, nested
		$this->assertEquals(false,     $Nest->exists(["one", "BAD"]));
		// Invalid, nested
		$this->assertEquals(false,     $Nest->exists(["BAD", "two"]));
	}

	public function testIsset() {
		$Nest = new Nest();
		// Valid
		$Nest->data($this->data);
		// First level
		$this->assertEquals(true,      isset($Nest->foo));
		// Nested
		$this->assertEquals(true,      isset($Nest->one__two));

		// Invalid, first level
		$this->assertEquals(false,     isset($Nest->BAD));
		// Invalid, nested
		$this->assertEquals(false,     isset($Nest->one__BAD));
		// Invalid, nested
		$this->assertEquals(false,     isset($Nest->BAD__TWO));
	}

}