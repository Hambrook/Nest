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

class arrayMathTest extends PHPUnit_Framework_TestCase {
	private $data = [
		"zero" => 0,
		"notzero" => 5,
		"one" => [
			"two" => 3
		]
	];

	public function testCreatePlus() {
		$Nest = new Nest($this->data);
		$this->assertInstanceOf("\Hambrook\Nest", $Nest);
		return $Nest;
	}

	public function testCreateMinus() {
		$Nest = new Nest($this->data);
		$this->assertInstanceOf("\Hambrook\Nest", $Nest);
		return $Nest;
	}

	/**
	 * @depends testCreatePlus
	 */
	public function testPlus($Nest) {
		// Default
		$this->assertEquals($Nest->plus("zero")->zero, 1);
		// Default again
		$this->assertEquals($Nest->plus("zero")->zero, 2);
		// Plus 2
		$this->assertEquals($Nest->plus("zero", 2)->zero, 4);
		// Default
		$this->assertEquals($Nest->plus("notzero")->notzero, 6);
		// Default again
		$this->assertEquals($Nest->plus("notzero")->notzero, 7);
		// Plus 2
		$this->assertEquals($Nest->plus("notzero", 2)->notzero, 9);
	}

	/**
	 * @depends testCreatePlus
	 */
	public function testPlusEmpty($Nest) {
		// Default
		$this->assertEquals($Nest->plus("empty")->empty, 1);
		// Default again
		$this->assertEquals($Nest->plus("empty")->empty, 2);
		// Plus 2
		$this->assertEquals($Nest->plus("empty", 2)->empty, 4);
	}

	/**
	 * @depends testCreatePlus
	 */
	public function testPlusNested($Nest) {
		// Valid
		$this->assertEquals($Nest->plus(["one", "two"])->one__two, 4);
		// Valid, with default
		$this->assertEquals($Nest->plus(["one", "two"])->one__two, 5);
		// Valid, with default
		$this->assertEquals($Nest->plus(["one", "two"], 2)->one__two, 7);
		// Invalid first, no default
		$this->assertEquals($Nest->plus(["BAD", "two"])->BAD__two, 1);
		// Invalid second, no default
		$this->assertEquals($Nest->plus(["one", "BAD"])->one__BAD, 1);
	}

	/**
	 * @depends testCreateMinus
	 */
	public function testMinus($Nest) {
		// Default
		$this->assertEquals($Nest->minus("zero")->zero, -1);
		// Default again
		$this->assertEquals($Nest->minus("zero")->zero, -2);
		// Minus 2
		$this->assertEquals($Nest->minus("zero", 2)->zero, -4);
		// Default
		$this->assertEquals($Nest->minus("notzero")->notzero, 4);
		// Default again
		$this->assertEquals($Nest->minus("notzero")->notzero, 3);
		// Plus 2
		$this->assertEquals($Nest->minus("notzero", 2)->notzero, 1);
	}

	/**
	 * @depends testCreateMinus
	 */
	public function testMinusEmpty($Nest) {
		// Default
		$this->assertEquals($Nest->minus("empty")->empty, -1);
		// Default again
		$this->assertEquals($Nest->minus("empty")->empty, -2);
		// Minus 2
		$this->assertEquals($Nest->minus("empty", 2)->empty, -4);
	}

	/**
	 * @depends testCreateMinus
	 */
	public function testMinusNested($Nest) {
		// Valid
		$this->assertEquals($Nest->minus(["one", "two"])->one__two, 2);
		// Valid, with default
		$this->assertEquals($Nest->minus(["one", "two"])->one__two, 1);
		// Valid, with default
		$this->assertEquals($Nest->minus(["one", "two"], 2)->one__two, -1);
		// Invalid first, no default
		$this->assertEquals($Nest->minus(["BAD", "two"])->BAD__two, -1);
		// Invalid second, no default
		$this->assertEquals($Nest->minus(["one", "BAD"])->one__BAD, -1);
	}

}