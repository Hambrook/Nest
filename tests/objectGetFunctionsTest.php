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
		$this->assertEquals("noparam", $Nest->get("conditionalstring"));
		// Valid, with default
		$this->assertEquals("noparam", $Nest->get("conditionalstring", "DEFAULT"));
		// Valid, with param
		$this->assertEquals("withparam", $Nest->get([["conditionalstring", true]]));
		// Invalid, no default
		$this->assertEquals(null,      $Nest->get("BAD"));
		// Invalid, with default
		$this->assertEquals("DEFAULT", $Nest->get("BAD", "DEFAULT"));
	}

	/**
	 * @depends testCreate
	 */
	public function testGetNested($Nest) {
		// Valid, with param
		$this->assertEquals("three",   $Nest->get("arrayfromfuncwithparams"));
		// Valid, with param
		$this->assertEquals("three",   $Nest->get([["arrayfromfuncwithparams", false]]));
		// Valid, with param
		$this->assertEquals("two",     $Nest->get([["arrayfromfuncwithparams", true], "one"]));
		// Valid, with param and defualt
		$this->assertEquals("two",     $Nest->get([["arrayfromfuncwithparams", true], "one"], "DEFAULT"));
		// Invalid, with param and default
		$this->assertEquals("DEFAULT", $Nest->get([["arrayfromfuncwithparams", true], "bad"], "DEFAULT"));
		// Invalid, with default
		$this->assertEquals("four",    $Nest->get([["BAD", true], "BAD"], "four"));
	}

	/**
	 * @depends testCreate
	 */
	public function testGetMagic($Nest) {
		// Valid, 1 level)
		$this->assertEquals("noparam", $Nest->conditionalstring);
		// Invalid, 1 level
		$this->assertEquals("noparam", $Nest->conditionalstring("DEFAULT"));
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
		// Invalid
		$this->assertEquals(null,      $Nest->BAD__two);
		// Invalid
		$this->assertEquals(null,      $Nest->one__BAD);
		// Valid, with default
		$this->assertEquals("three",   $Nest->one__two("default"));
		// Invalid first, with default
		$this->assertEquals("default", $Nest->BAD__two("default"));
		// Invalid second, with default
		$this->assertEquals("default", $Nest->one__BAD("default"));
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