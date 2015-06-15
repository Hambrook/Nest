<?php

define("_LIB_PATH", dirname(__FILE__)."/../src/Nest.php");
$tests = glob(dirname(__FILE__)."/_test-*.php");

class Tester {
	public $cntPass = [];
	public $cntFail = [];

	public function test($value, $tests) {
		foreach ($tests as $k => $t) {
			if (eval('return '.$t[0].';') === $t[1]) {
				$this->cntPass[] = $k;
			} else {
				$this->cntFail[] = $k;
			}
		}
	}

	public function results() {
		echo sprintf(
			"\nProcessed: %s, Pass: %s, Fail: %s\n\n",
			(count($this->cntPass) + count($this->cntFail)),
			count($this->cntPass),
			count($this->cntFail)
		);
	}
}

$Tester = new Tester();

foreach ($tests as $testSet) {
	include_once($testSet);
}

$Tester->results();

//todo: add tests for other functions (count, merge, json, etc)