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

class arrayIsSetTest extends PHPUnit_Framework_TestCase {

	public function testCreate() {
		$Nest = new Nest(
			[
				"foo" => "bar",
				"one" => [
					"two" => "three"
				]
			]
		);
		$this->assertInstanceOf("\Hambrook\Nest", $Nest);
		return $Nest;
	}

	/**
	 * @depends testCreate
	 */
	public function testIsSet($Nest) {
		// Is set
		$this->assertEquals(true,      $Nest->exists("foo"));
		// Is not set
		$this->assertEquals(false,     $Nest->exists("BAD"));
	}

	/**
	 * @depends testCreate
	 */
	public function testIsSetNested($Nest) {
		// Valid
		$this->assertEquals(true,      $Nest->exists(["one", "two"]));
		// Invalid first, no default
		$this->assertEquals(false,     $Nest->exists(["BAD", "two"]));
		// Invalid second, no default
		$this->assertEquals(false,     $Nest->exists(["one", "BAD"]));
	}

	/**
	 * @depends testCreate
	 */
	public function testIsSetMagic($Nest) {
		// Valid, 1 level)
		$this->assertEquals(true,      isset($Nest->foo));
		// Invalid, 1 level, with default
		$this->assertEquals(false,     isset($Nest->BAD));
	}

	/**
	 * @depends testCreate
	 */
	public function testIsSetNestedMagic($Nest) {
		// Valid
		$this->assertEquals(true,      isset($Nest->one__two));
		// Invalid first, no default
		$this->assertEquals(false,     isset($Nest->BAD__two));
		// Invalid second, no default
		$this->assertEquals(false,     isset($Nest->one__BAD));
	}

}