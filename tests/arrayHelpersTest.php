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
		$this->assertEquals("four",    $Nest->one__0);
		$this->assertEquals("five",    $Nest->one__1);
	}

	/**
	 * @depends testCreateCount
	 */
	public function testCount($Nest) {
		// No default
		$this->assertEquals(2,         $Nest->count());
		// Default
		$this->assertEquals(2,         $Nest->count(false, 5));
		// Nested
		$this->assertEquals(1,         $Nest->count("one"));
		// Updated nested count
		$Nest->one__four = "five";
		$this->assertEquals(2,         $Nest->count("one"));
	}

	/**
	 * @depends testCreateMerge
	 */
	public function testMerge($Nest) {
		$Nest->merge("one", [
			"four" => "five"
		]);

		// Existing value
		$this->assertEquals("three",   $Nest->one__two);
		// New value
		$this->assertEquals("five",    $Nest->one__four);
		// Count
		$this->assertEquals(2,         $Nest->count("one"));
		// Old top level value
		$this->assertEquals("bar",     $Nest->foo);
	}

}