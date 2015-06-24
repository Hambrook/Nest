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
		$this->assertEquals($Nest->foo, "bar");
		// Nested
		$this->assertEquals($Nest->one__two, "three");

		$Nest = new Nest();
		// Valid
		$Nest->data(["bar" => "baz"]);
		// First level
		$this->assertEquals($Nest->bar, "baz");
		// Invalid, first level
		$this->assertEquals($Nest->foo, null);
		// Invalid, nested
		$this->assertEquals($Nest->one__two, null);
	}

	public function testTo() {
		$Nest = new Nest();
		// Valid
		$Nest->data($this->data);
		$this->assertEquals($this->data, $Nest->data());
	}

}