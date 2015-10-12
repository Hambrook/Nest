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
class jsonTest extends PHPUnit_Framework_TestCase {

	public function testCreate() {
		$Nest = new Nest([]);
		$this->assertInstanceOf("\Hambrook\Nest\Nest", $Nest);
		return $Nest;
	}
	/**
	 * @depends testCreate
	 */
	public function testLoad($Nest) {
		// Valid
		$json = '{"foo":"bar","one":{"two":"three"}}';
		$Nest->loadJSON($json);
		// Valid
		$this->assertEquals("bar",     $Nest->foo);
		// Valid, with default
		$this->assertEquals("three",   $Nest->one__two);
	}

	/**
	 * @depends testCreate
	 */
	public function testTo($Nest) {
		$Nest->foo = "newfoo";
		$Nest->one__two = "four";
		$json = $Nest->toJSON(false);
		$this->assertEquals('{"foo":"newfoo","one":{"two":"four"}}', $json);
	}

}