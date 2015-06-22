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

class arrayHelpersTest extends PHPUnit_Framework_TestCase {

	private $data = [
		"foo" => "bar",
		"one" => [
			"two" => "three"
		]
	];

	public function testCreateAppend() {
		$Nest = new Nest($this->data);
		return $Nest;
	}

	public function testCreateCount() {
		$Nest = new Nest($this->data);
		return $Nest;
	}

	public function testCreateMerge() {
		$Nest = new Nest($this->data);
		return $Nest;
	}

	/**
	 * @depends testCreateAppend
	 */
	public function testAppend($Nest) {
		$Nest->append("one", "four");
		$Nest->append("one", "five");
		// Default
		$this->assertEquals($Nest->one__0, "four");
		$this->assertEquals($Nest->one__1, "five");
	}

	/**
	 * @depends testCreateCount
	 */
	public function testCount($Nest) {
		// No default
		$this->assertEquals($Nest->count(), 2);
		// Default
		$this->assertEquals($Nest->count(false, 5), 2);
		// Nested
		$this->assertEquals($Nest->count("one"), 1);
		// Updated nested count
		$Nest->one__four = "five";
		$this->assertEquals($Nest->count("one"), 2);
	}

	/**
	 * @depends testCreateMerge
	 */
	public function testMerge($Nest) {
		$Nest->merge("one", [
			"four" => "five"
		]);

		// Existing value
		$this->assertEquals($Nest->one__two, "three");
		// New value
		$this->assertEquals($Nest->one__four, "five");
		// Count
		$this->assertEquals($Nest->count("one"), 2);
		// Old top level value
		$this->assertEquals($Nest->foo, "bar");
	}

}