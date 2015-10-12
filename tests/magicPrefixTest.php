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
class magicPrefixTest extends PHPUnit_Framework_TestCase {

	public function testSetMagic() {
		$Nest = new Nest([]);
		// Valid, 1 level)
		$Nest->__data = "bar";
		$this->assertEquals("bar",     $Nest->__data);
		$this->assertEquals("bar",     $Nest->data);
	}

	public function testSetNestedMagic() {
		$Nest = new Nest([]);
		// Invalid, 1 level
		$Nest->__plus__minus = "four";
		$this->assertEquals("four",    $Nest->__plus__minus);
	}

}