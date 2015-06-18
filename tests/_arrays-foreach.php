<?php

$array = [
	"one" => "a",
	"two" => "b",
	"three" => "c",
];
$value = new \Hambrook\Nest($array);

$Tester->test(
	$value,
	[
		'(array) foreach (keyed)' => function($v) {
			$tmp = "";
			foreach ($v as $t) {
				$tmp .= $t;
			}
			return ($tmp == "abc");
		}
	]
);


$value = new \Hambrook\Nest(array_values($array));

$Tester->test(
	$value,
	[
		'(array) foreach (indexed)' => function($v) {
			$tmp = "";
			foreach ($v as $t) {
				$tmp .= $t;
			}
			return ($tmp == "abc");
		}
	]
);