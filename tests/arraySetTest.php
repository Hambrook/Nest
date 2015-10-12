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
class arraySetTest extends PHPUnit_Framework_TestCase {

	public function testSet() {
		$Nest = new Nest([]);
		// Setting empty array
		$this->assertEquals([],        $Nest->set("foo")->get("foo"));
		// Setting value
		$this->assertEquals("baz",     $Nest->set("bar", "baz")->get("bar"));
	}

	public function testSetNested() {
		$Nest = new Nest([]);
		// Setting empty array
		$key = ["one", "two"];
		$this->assertEquals([],        $Nest->set($key)->get($key));
		// Valid
		$this->assertEquals("three",   $Nest->set($key, "three")->get($key));
	}

	public function testSetMagic() {
		$Nest = new Nest([]);
		// Valid, 1 level)
		$Nest->foo = "bar";
		$this->assertEquals("bar",     $Nest->foo);
	}

	public function testSetNestedMagic() {
		$Nest = new Nest([]);
		// Invalid, 1 level
		$Nest->one__two = "four";
		$this->assertEquals("four",    $Nest->one__two);
	}

}