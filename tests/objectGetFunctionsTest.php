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

class objectGetFunctionsTest extends PHPUnit_Framework_TestCase {

	public function testCreate() {
		$Nest = new Nest(new objectGetFunctionsTestData);
		$this->assertInstanceOf("\Hambrook\Nest", $Nest);
		return $Nest;
	}

	/**
	 * @depends testCreate
	 */
	public function testGet($Nest) {
		// Valid
		$this->assertEquals($Nest->get("conditionalstring"), "noparam");
		// Valid, with default
		$this->assertEquals($Nest->get("conditionalstring", "DEFAULT"), "noparam");
		// Valid, with param
		$this->assertEquals($Nest->get([["conditionalstring", true]]), "withparam");
		// Invalid, no default
		$this->assertEquals($Nest->get("BAD"), null);
		// Invalid, with default
		$this->assertEquals($Nest->get("BAD", "DEFAULT"), "DEFAULT");
	}

	/**
	 * @depends testCreate
	 */
	public function testGetNested($Nest) {
		// Valid, with param
		$this->assertEquals($Nest->get("arrayfromfuncwithparams"), "three");
		// Valid, with param
		$this->assertEquals($Nest->get([["arrayfromfuncwithparams", false]]), "three");
		// Valid, with param
		$this->assertEquals($Nest->get([["arrayfromfuncwithparams", true], "one"]), "two");
		// Valid, with param and defualt
		$this->assertEquals($Nest->get([["arrayfromfuncwithparams", true], "one"], "DEFAULT"), "two");
		// Invalid, with param and default
		$this->assertEquals($Nest->get([["arrayfromfuncwithparams", true], "bad"], "DEFAULT"), "DEFAULT");
		// Invalid, with default
		$this->assertEquals($Nest->get([["BAD", true], "BAD"], "four"), "four");
	}

	/**
	 * @depends testCreate
	 */
	public function testGetMagic($Nest) {
		// Valid, 1 level)
		$this->assertEquals($Nest->conditionalstring, "noparam");
		// Invalid, 1 level
		$this->assertEquals($Nest->conditionalstring("DEFAULT"), "noparam");
		// Invalid, 1 level, with default
		$this->assertEquals($Nest->BAD, null);
		// Invalid, 1 level, with default
		$this->assertEquals($Nest->BAD("DEFAULT"), "DEFAULT");
	}

	/**
	 * @depends testCreate
	 */
	public function testGetNestedMagic($Nest) {
		// Valid
		$this->assertEquals($Nest->one__two, "three");
		// Invalid
		$this->assertEquals($Nest->BAD__two, null);
		// Invalid
		$this->assertEquals($Nest->one__BAD, null);
		// Valid, with default
		$this->assertEquals($Nest->one__two("default"), "three");
		// Invalid first, with default
		$this->assertEquals($Nest->BAD__two("default"), "default");
		// Invalid second, with default
		$this->assertEquals($Nest->one__BAD("default"), "default");
	}

}

class objectGetFunctionsTestData {
	function conditionalstring($v=false) {
		return ($v) ? "withparam" : "noparam";
	}
	function one() {
		return ["two" => "three"];
	}
	function arrayfromfuncwithparams($v=false) {
		return ($v) ? ["one" => "two"] : "three";
	}
}