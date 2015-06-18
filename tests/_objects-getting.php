<?php

class testObjectGetting {
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
$value = new \Hambrook\Nest(new testObjectGetting());

$Tester->test(
	$value,
	[
		'(object)->get("foo")' => function($v) {
			return ($v->get("foo") == "bar");
		},
		'(object)->get("foo", "baz")' => function($v) {
			return ($v->get("foo", "baz") == "bar");
		},
		'(object)->get("foo2", "baz")' => function($v) {
			return ($v->get("foo2", "baz") == "baz");
		},
		'(object)->foo' => function($v) {
			return ($v->foo == "bar");
		},
		'(object)->foo("baz")' => function($v) {
			return ($v->foo("baz") == "bar");
		},
		'(object)->foo2("baz")' => function($v) {
			return ($v->foo2("baz") == "baz");
		},
		'(object)->get(["one", "two"])' => function($v) {
			return ($v->get(["one", "two"]) == "three");
		},
		'(object)->get(["one", "two"], "four")' => function($v) {
			return ($v->get(["one", "two"], "four") == "three");
		},
		'(object)->get(["one", "bad"], "four")' => function($v) {
			return ($v->get(["one", "bad"], "four") == "four");
		},
		'(object)->one__two' => function($v) {
			return ($v->one__two == "three");
		},
		'(object)->one__two("four")' => function($v) {
			return ($v->one__two("four") == "three");
		},
		'(object)->one__two2("four")' => function($v) {
			return ($v->one__two2("four") == "four");
		},
		'(object)->get(["one2", "two"])' => function($v) {
			return ($v->get(["one2", "two"]) == "three");
		},
		'(object)->get(["one2", "two"], "four")' => function($v) {
			return ($v->get(["one2", "two"], "four") == "three");
		},
		'(object)->get(["one2", "bad"], "four")' => function($v) {
			return ($v->get(["one2", "bad"], "four") == "four");
		},
		'(object)->one2__two' => function($v) {
			return ($v->one2__two == "three");
		},
		'(object)->one2__two("four")' => function($v) {
			return ($v->one2__two("four") == "three");
		},
		'(object)->one2__bad("four")' => function($v) {
			return ($v->one2__bad("four") == "four");
		},
		'(object)->get("conditionalstring")' => function($v) {
			return ($v->get("conditionalstring") == "noparam");
		},
		'(object)->get([["conditionalstring", true]])' => function($v) {
			return ($v->get([["conditionalstring", true]]) == "withparam");
		},
		'(object)->get("conditionalstring", "four")' => function($v) {
			return ($v->get("conditionalstring", "four") == "noparam");
		},
		'(object)->get("bad", "four")' => function($v) {
			return ($v->get("bad", "four") == "four");
		},
		'(object)->get([["arrayfromfuncwithparams", true], "one"])' => function($v) {
			return ($v->get([["arrayfromfuncwithparams", true], "one"]) == "two");
		},
		'(object)->get([["arrayfromfuncwithparams", true], "one"], "four")' => function($v) {
			return ($v->get([["arrayfromfuncwithparams", true], "one"], "four") == "two");
		},
		'(object)->get([["arrayfromfuncwithparams", true], "bad"], "four")' => function($v) {
			return ($v->get([["arrayfromfuncwithparams", true], "bad"], "four") == "four");
		},
		'(object)->get([["bad", true], "bad"], "four")' => function($v) {
			return ($v->get([["bad", true], "bad"], "four") == "four");
		},
	]
);