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
class dataTest extends PHPUnit_Framework_TestCase {

	private $data = [
		"foo" => "bar",
		"one" => [
			"two" => "three"
		]
	];

	public function testSet() {
		$Nest = new Nest();
		// Valid
		$Nest->data($this->data);
		// First level
		$this->assertEquals("bar",     $Nest->foo);
		// Nested
		$this->assertEquals("three",   $Nest->one__two);

		$Nest = new Nest();
		// Valid
		$Nest->data(["bar" => "baz"]);
		// First level
		$this->assertEquals("baz",     $Nest->bar);
		// Invalid, first level
		$this->assertEquals(null,      $Nest->foo);
		// Invalid, nested
		$this->assertEquals(null,      $Nest->one__two);
	}

	public function testTo() {
		$Nest = new Nest();
		// Valid
		$Nest->data($this->data);
		$this->assertEquals($this->data, $Nest->data());
	}

}