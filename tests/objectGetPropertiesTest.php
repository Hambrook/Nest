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

class objectGetPropertiesTest extends PHPUnit_Framework_TestCase {

	public function testCreate() {
		$Nest = new Nest(new objectGetPropertiesTestData);
		$this->assertInstanceOf("\Hambrook\Nest", $Nest);
		return $Nest;
	}

	/**
	 * @depends testCreate
	 */
	public function testGet($Nest) {
		// Valid
		$this->assertEquals($Nest->get("foo"), "bar");
		// Valid, with default
		$this->assertEquals($Nest->get("foo", "DEFAULT"), "bar");
		// Invalid, no default
		$this->assertEquals($Nest->get("BAD"), null);
		// Invalid, with default
		$this->assertEquals($Nest->get("BAD", "DEFAULT"), "DEFAULT");
	}

	/**
	 * @depends testCreate
	 */
	public function testGetNested($Nest) {
		// Valid
		$this->assertEquals($Nest->get(["one", "two"]), "three");
		// Valid, with default
		$this->assertEquals($Nest->get(["one", "two"], "DEFAULT"), "three");
		// Invalid first, no default
		$this->assertEquals($Nest->get(["BAD", "two"]), null);
		// Invalid second, no default
		$this->assertEquals($Nest->get(["one", "BAD"]), null);
		// Invalid first, with default
		$this->assertEquals($Nest->get(["BAD", "two"], "DEFAULT"), "DEFAULT");
		// Invalid second, with default
		$this->assertEquals($Nest->get(["one", "BAD"], "DEFAULT"), "DEFAULT");
	}

	/**
	 * @depends testCreate
	 */
	public function testMagicGet($Nest) {
		// Valid, 1 level)
		$this->assertEquals($Nest->foo, "bar");
		// Invalid, 1 level
		$this->assertEquals($Nest->foo("DEFAULT"), "bar");
		// Invalid, 1 level, with default
		$this->assertEquals($Nest->BAD, null);
		// Invalid, 1 level, with default
		$this->assertEquals($Nest->BAD("DEFAULT"), "DEFAULT");
	}

	/**
	 * @depends testCreate
	 */
	public function testMagicGetNested($Nest) {
		// Valid
		$this->assertEquals($Nest->one__two, "three");
		// Valid, with default
		$this->assertEquals($Nest->one__two("DEFAULT"), "three");
		// Invalid first, no default
		$this->assertEquals($Nest->BAD__two, null);
		// Invalid second, no default
		$this->assertEquals($Nest->one__BAD, null);
		// Invalid first, with default
		$this->assertEquals($Nest->BAD__two("DEFAULT"), "DEFAULT");
		// Invalid second, with default
		$this->assertEquals($Nest->one__BAD("DEFAULT"), "DEFAULT");
	}

}

class objectGetPropertiesTestData {
	public $foo = "bar";
	public $one = [
		"two" => "three"
	];
}