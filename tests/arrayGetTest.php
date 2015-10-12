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
class arrayGetTest extends PHPUnit_Framework_TestCase {

	public function testCreate() {
		$Nest = new Nest(
			[
				"foo" => "bar",
				"one" => [
					"two" => "three"
				]
			]
		);
		$this->assertInstanceOf("\Hambrook\Nest\Nest", $Nest);
		return $Nest;
	}

	/**
	 * @depends testCreate
	 */
	public function testGet($Nest) {
		// Valid
		$this->assertEquals("bar",     $Nest->get("foo"));
		// Valid, with default
		$this->assertEquals("bar",     $Nest->get("foo", "DEFAULT"));
		// Invalid, no default
		$this->assertEquals(null,      $Nest->get("BAD"));
		// Invalid, with default
		$this->assertEquals("DEFAULT", $Nest->get("BAD", "DEFAULT"));
	}

	/**
	 * @depends testCreate
	 */
	public function testGetNested($Nest) {
		// Valid
		$this->assertEquals("three",   $Nest->get(["one", "two"]));
		// Valid, with default
		$this->assertEquals("three",   $Nest->get(["one", "two"], "DEFAULT"));
		// Invalid first, no default
		$this->assertEquals(null,      $Nest->get(["BAD", "two"]));
		// Invalid second, no default
		$this->assertEquals(null,      $Nest->get(["one", "BAD"]));
		// Invalid first, with default
		$this->assertEquals("DEFAULT", $Nest->get(["BAD", "two"], "DEFAULT"));
		// Invalid second, with default
		$this->assertEquals("DEFAULT", $Nest->get(["one", "BAD"], "DEFAULT"));
	}

	/**
	 * @depends testCreate
	 */
	public function testGetMagic($Nest) {
		// Valid, 1 level)
		$this->assertEquals("bar",     $Nest->foo);
		// Invalid, 1 level
		$this->assertEquals("bar",     $Nest->foo("DEFAULT"));
		// Invalid, 1 level, with default
		$this->assertEquals(null,      $Nest->BAD);
		// Invalid, 1 level, with default
		$this->assertEquals("DEFAULT", $Nest->BAD("DEFAULT"));
	}

	/**
	 * @depends testCreate
	 */
	public function testGetNestedMagic($Nest) {
		// Valid
		$this->assertEquals("three",   $Nest->one__two);
		// Valid, with default
		$this->assertEquals("three",   $Nest->one__two("DEFAULT"));
		// Invalid first, no default
		$this->assertEquals(null,      $Nest->BAD__two);
		// Invalid second, no default
		$this->assertEquals(null,      $Nest->one__BAD);
		// Invalid first, with default
		$this->assertEquals("DEFAULT", $Nest->BAD__two("DEFAULT"));
		// Invalid second, with default
		$this->assertEquals("DEFAULT", $Nest->one__BAD("DEFAULT"));
	}

}