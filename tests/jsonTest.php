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

class jsonTest extends PHPUnit_Framework_TestCase {

	public function testCreate() {
		$Nest = new Nest([]);
		$this->assertInstanceOf("\Hambrook\Nest", $Nest);
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
		$this->assertEquals($Nest->foo, "bar");
		// Valid, with default
		$this->assertEquals($Nest->one__two, "three");
	}

	/**
	 * @depends testCreate
	 */
	public function testTo($Nest) {
		$Nest->foo = "newfoo";
		$Nest->one__two = "four";
		$json = $Nest->toJSON(false);
		$this->assertEquals($json, '{"foo":"newfoo","one":{"two":"four"}}');
	}

}