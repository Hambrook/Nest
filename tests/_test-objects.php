<?php

require_once(_LIB_PATH);

class Foo {
	public $foo = "bar";
	public $one = [
		"two" => "three"
	];
	function conditionalstring($v=false) {
		return ($v) ? "withparam" : "noparam";
	}
	function one2() {
		return ["two" => "three"];
	}
	function arrayfromfuncwithparams($v=false) {
		return ($v) ? ["one" => "two"] : "three";
	}
}

$value = new \Hambrook\Nest(new Foo());

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
			'$value->get("foo2", "baz")',
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
			'$value->foo2("baz")',
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

		],
		[
			'$value->one__two2("four")',
			"four"

		],
		[
			'$value->get(["one2", "two"])',
			"three"

		],
		[
			'$value->get(["one2", "two"], "four")',
			"three"

		],
		[
			'$value->get(["one2", "bad"], "four")',
			"four"

		],
		[
			'$value->one2__two',
			"three"

		],
		[
			'$value->one2__two("four")',
			"three"

		],
		[
			'$value->one2__bad("four")',
			"four"

		],
		[
			'$value->get("conditionalstring")',
			"noparam"

		],
		[
			'$value->get([["conditionalstring", true]])',
			"withparam"

		],
		[
			'$value->get("conditionalstring", "four")',
			"noparam"

		],
		[
			'$value->get("bad", "four")',
			"four"

		],
		[
			'$value->get([["arrayfromfuncwithparams", true], "one"])',
			"two"
		],
		[
			'$value->get([["arrayfromfuncwithparams", true], "one"], "four")',
			"two"
		],
		[
			'$value->get([["arrayfromfuncwithparams", true], "bad"], "four")',
		"four"
		],
		[
			'$value->get([["bad", true], "bad"], "four")',
			"four"
		]
	]
);