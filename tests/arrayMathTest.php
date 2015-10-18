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
		$this->assertInstanceOf("\Hambrook\Nest\Nest", $Nest);
		return $Nest;
	}

	public function testCreateMinus() {
		$Nest = new Nest($this->data);
		$this->assertInstanceOf("\Hambrook\Nest\Nest", $Nest);
		return $Nest;
	}

	/**
	 * @depends testCreatePlus
	 */
	public function testPlus($Nest) {
		// Default
		$this->assertEquals(1,         $Nest->plus("zero")->zero);
		// Default again
		$this->assertEquals(2,         $Nest->plus("zero")->zero);
		// Plus 2
		$this->assertEquals(4,         $Nest->plus("zero", 2)->zero);
		// Default
		$this->assertEquals(6,         $Nest->plus("notzero")->notzero);
		// Default again
		$this->assertEquals(7,         $Nest->plus("notzero")->notzero);
		// Plus 2
		$this->assertEquals(9,         $Nest->plus("notzero", 2)->notzero);
	}

	/**
	 * @depends testCreatePlus
	 */
	public function testPlusEmpty($Nest) {
		// Default
		$this->assertEquals(1,         $Nest->plus("empty")->empty);
		// Default again
		$this->assertEquals(2,         $Nest->plus("empty")->empty);
		// Plus 2
		$this->assertEquals(4,         $Nest->plus("empty", 2)->empty);
		// Default
		$this->assertEquals(3,         $Nest->plus("empty2", 1, 2)->empty2);
		// Default again
		$this->assertEquals(4,         $Nest->plus("empty2")->empty2);
		// Plus 2
		$this->assertEquals(6,         $Nest->plus("empty2", 2)->empty2);
	}

	/**
	 * @depends testCreatePlus
	 */
	public function testPlusNested($Nest) {
		// Valid
		$this->assertEquals(4,         $Nest->plus(["one", "two"])->one__two);
		// Valid, with default
		$this->assertEquals(5,         $Nest->plus(["one", "two"])->one__two);
		// Valid, with default
		$this->assertEquals(7,         $Nest->plus(["one", "two"], 2)->one__two);
		// Invalid first, no default
		$this->assertEquals(1,         $Nest->plus(["BAD", "two"])->BAD__two);
		// Invalid second, no default
		$this->assertEquals(1,         $Nest->plus(["one", "BAD"])->one__BAD);
	}

	/**
	 * @depends testCreateMinus
	 */
	public function testMinus($Nest) {
		// Default
		$this->assertEquals(-1,        $Nest->minus("zero")->zero);
		// Default again
		$this->assertEquals(-2,        $Nest->minus("zero")->zero);
		// Minus 2
		$this->assertEquals(-4,        $Nest->minus("zero", 2)->zero);
		// Default
		$this->assertEquals(4,         $Nest->minus("notzero")->notzero);
		// Default again
		$this->assertEquals(3,         $Nest->minus("notzero")->notzero);
		// Plus 2
		$this->assertEquals(1,         $Nest->minus("notzero", 2)->notzero);
	}

	/**
	 * @depends testCreateMinus
	 */
	public function testMinusEmpty($Nest) {
		// Default
		$this->assertEquals(-1,        $Nest->minus("empty")->empty);
		// Default again
		$this->assertEquals(-2,        $Nest->minus("empty")->empty);
		// Minus 2
		$this->assertEquals(-4,        $Nest->minus("empty", 2)->empty);
	}

	/**
	 * @depends testCreateMinus
	 */
	public function testMinusNested($Nest) {
		// Valid
		$this->assertEquals(2,         $Nest->minus(["one", "two"])->one__two);
		// Valid, with default
		$this->assertEquals(1,         $Nest->minus(["one", "two"])->one__two);
		// Valid, with default
		$this->assertEquals(-1,        $Nest->minus(["one", "two"], 2)->one__two);
		// Invalid first, no default
		$this->assertEquals(-1,        $Nest->minus(["BAD", "two"])->BAD__two);
		// Invalid second, no default
		$this->assertEquals(-1,        $Nest->minus(["one", "BAD"])->one__BAD);
	}

}