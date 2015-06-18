<?php

class testObjectForeach {
	public $one = "a";
	public $two = "b";
	public $three = "c";
}
$value = new \Hambrook\Nest(new testObjectForeach());

$Tester->test(
	$value,
	[
		'(object) foreach' => function($v) {
			$tmp = "";
			foreach ($v as $t) {
				$tmp .= $t;
			}
			return ($tmp == "abc");
		}
	]
);