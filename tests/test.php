<?php

define("_LIB_PATH", dirname(__FILE__)."/../src/Nest.php");
$tests = glob(dirname(__FILE__)."/_*.php");

require_once(_LIB_PATH);

class Tester {
	public $passed = [];
	public $failed = [];

	public function test($value, $tests) {
		foreach ($tests as $k => $f) {
			if (is_callable($f)) {
				if (call_user_func($f, $value)) {
					$this->passed[$k] = $f;
				} else {
					$this->failed[$k] = $f;
				}
			}
		}
	}

	public function results() {
		if (count($this->failed)) {
			echo "\nFAILED TESTS\n";
			foreach ($this->failed as $k => $t) {
				echo sprintf("\t%s\n", $k);
			}
		}
		echo sprintf(
			"\nProcessed: %s, Pass: %s, Fail: %s\n\n",
			(count($this->passed) + count($this->failed)),
			count($this->passed),
			count($this->failed)
		);
	}
}

$Tester = new Tester();

foreach ($tests as $testSet) {
	include_once($testSet);
}

$Tester->results();

//todo: add tests for other functions (count, merge, json, etc)