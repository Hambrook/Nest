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
class arrayForeachTest extends PHPUnit_Framework_TestCase {

	public function testForeachIndexed() {
		$array = [
			"one"   => "a",
			"two"   => "b",
			"three" => "c",
		];

		$Nest = new Nest($array);
		$tmp = "";
		foreach ($Nest as $t) {
			$tmp .= $t;
		}
		$this->assertEquals("abc", $tmp);
	}

	public function testForeachKeyed() {
		$array = [
			"a",
			"b",
			"c",
		];

		$Nest = new Nest($array);
		$tmp = "";
		foreach ($Nest as $t) {
			$tmp .= $t;
		}
		$this->assertEquals("abc", $tmp);
	}

}