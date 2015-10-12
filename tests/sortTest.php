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
class sortTest extends PHPUnit_Framework_TestCase {

	private $data = [
		"foo" => "bar",
		"one" => [
			"z" => "zz",
			 3  => 33,
			"a" => "aa",
			 1  => 11,
			"B" => "BB",
			 2  => 22
		]
	];

	public function testAsort() {
		$Nest = new Nest($this->data);
		$data = $this->data["one"];
		asort($data);
		$this->assertEquals($data,     $Nest->sort("one", "a")->get("one"));
	}

	public function testARsort() {
		$Nest = new Nest($this->data);
		$data = $this->data["one"];
		arsort($data);
		$this->assertEquals($data,     $Nest->sort("one", "ar")->get("one"));
	}

	public function testKsort() {
		$Nest = new Nest($this->data);
		$data = $this->data["one"];
		ksort($data);
		$this->assertEquals($data,     $Nest->sort("one", "k")->get("one"));
	}

	public function testKRsort() {
		$Nest = new Nest($this->data);
		$data = $this->data["one"];
		krsort($data);
		$this->assertEquals($data,     $Nest->sort("one", "kr")->get("one"));
	}

	public function testNatSort() {
		$Nest = new Nest($this->data);
		$data = $this->data["one"];
		natsort($data);
		$this->assertEquals($data,     $Nest->sort("one", "nat")->get("one"));
	}

	public function testNatCaseSort() {
		$Nest = new Nest($this->data);
		$data = $this->data["one"];
		natcasesort($data);
		$this->assertEquals($data,     $Nest->sort("one", "natcase")->get("one"));
	}

	/*
	public function testUASort() {
		$Nest = new Nest($this->data);
		$data = $this->data["one"];
		uasort($data);
		$this->assertEquals($data,     $Nest->sort("one", "ua")->get("one"));
	}

	public function testUKSort() {
		$Nest = new Nest($this->data);
		$data = $this->data["one"];
		uksort($data);
		$this->assertEquals($data,     $Nest->sort("one", "uk")->get("one"));
	}
	*/

	public function testFullNamesort() {
		$Nest = new Nest($this->data);
		$data = $this->data["one"];
		asort($data);
		$this->assertEquals($data,     $Nest->sort("one", "asort")->get("one"));
	}

}