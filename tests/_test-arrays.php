<?php

require_once(_LIB_PATH);

$array = [
	"foo" => "bar",
	"one" => [
		"two" => "three"
	]
];

$value = new \Hambrook\Nest($array);

$Tester->test(
	$value,
	[
		[
			'$value->get("foo")',
			"bar"
		],
		[
			'$value->get("foo", "baz")',
			"bar"
		],
		[
			'$value->get("bad", "baz")',
			"baz"
		],
		[
			'$value->foo',
			"bar"
		],
		[
			'$value->foo("baz")',
			"bar"
		],
		[
			'$value->bad("baz")',
			"baz"
		],
		[
			'$value->get(["one", "two"])',
			"three"

		],
		[
			'$value->get(["one", "two"], "four")',
			"three"
		],
		[
			'$value->get(["one", "bad"], "four")',
			"four"
		],
		[
			'$value->one__two',
			"three"
		],
		[
			'$value->one__two("four")',
			"three"
		]
	]
);