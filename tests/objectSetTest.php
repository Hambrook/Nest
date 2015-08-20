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

class objectSetTest extends PHPUnit_Framework_TestCase {

	public function testCreate() {
		$Nest = new Nest(new objectSetTestData);
		$this->assertInstanceOf("\Hambrook\Nest", $Nest);
		return $Nest;
	}

	/**
	 * @depends testCreate
	 */
	public function testSet() {
		$Nest = new Nest([]);
		// Setting empty array
		$this->assertEquals([],        $Nest->set("foo")->get("foo"));
		// Setting value
		$this->assertEquals("baz",     $Nest->set("bar", "baz")->get("bar"));
	}

	/**
	 * @depends testCreate
	 */
	public function testSetNested() {
		$Nest = new Nest([]);
		// Setting empty array
		$key = ["one", "two"];
		$this->assertEquals([],        $Nest->set($key)->get($key));
		// Valid
		$this->assertEquals("three",   $Nest->set($key, "three")->get($key));
	}

	/**
	 * @depends testCreate
	 */
	public function testSetMagic() {
		$Nest = new Nest([]);
		// Valid, 1 level)
		$Nest->foo = "bar";
		$this->assertEquals("bar",     $Nest->foo);
	}

	/**
	 * @depends testCreate
	 */
	public function testSetNestedMagic() {
		$Nest = new Nest([]);
		// Invalid, 1 level
		$Nest->one__two = "four";
		$this->assertEquals("four",    $Nest->one__two);
	}

}

class objectSetTestData {
	public $foo = "bar";
	public $one = [
		"two" => "three"
	];
}