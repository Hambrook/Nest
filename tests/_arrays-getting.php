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
		'(array)->get("foo")' => function($v) {
			return ($v->get("foo") == "bar");
		},
		'(array)->get("foo", "baz")' => function($v) {
			return ($v->get("foo", "baz") == "bar");
		},
		'(array)->get("bad", "baz")' => function($v) {
			return ($v->get("bad", "baz") == "baz");
		},
		'(array)->foo' => function($v) {
			return ($v->foo == "bar");
		},
		'(array)->foo("baz")' => function($v) {
			return ($v->foo("baz") == "bar");
		},
		'(array)->bad("baz")' => function($v) {
			return ($v->bad("baz") == "baz");
		},
		'(array)->get(["one", "two"])' => function($v) {
			return ($v->get(["one", "two"]) == "three");
		},
		'(array)->get(["one", "two"], "four")' => function($v) {
			return ($v->get(["one", "two"], "four") == "three");
		},
		'(array)->get(["one", "bad"], "four")' => function($v) {
			return ($v->get(["one", "bad"], "four") == "four");
		},
		'(array)->one__two' => function($v) {
			return ($v->one__two == "three");
		},
		'(array)->one__two("four")' => function($v) {
			return ($v->one__two("four") == "three");
		}
	]
);