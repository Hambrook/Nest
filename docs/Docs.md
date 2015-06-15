#Nest Usage
######By Rick Hambrook
-----

Just create new new **Nest()** object and pass it an array or object.

	$Nest = new Nest(
		[
			"foo" => "bar",
			"one" => [
				"two" => "three"
			]
		]
	);

Then you can set or get values as much as you want, without having to do validation checks first.

##The Path
Nest uses a `$path` parameter a lot. This is an array of keys, property names or function names to the value you want to work with.
The `$path` may be a string if you're only using one level.

For example, the path to `"bar"` above is `"foo"`, but the path to `"three"` is `["one", "two"]`.

##Getting
The first parameter is the path (an array of keys) to drill down to the value you want. Or just a string if it's only one level down you need.

If the path is invalid or the value isn't there, you'll get back the default value. This is either the second parameter (if you supplied it) or `null`.

	// first param can be a string (if getting single depth) or an array
	echo $Nest->get("foo");                                    // prints "bar"

	// with array path for nested values
	$Nest->get("one");                                         // returns ["two" => "three"]

	// with array path for nested values
	echo $Nest->get(["one", "two"]);                           // prints "three"

###Getting with default value (pass as second param) if path is invalid
	echo $Nest->get("bad", "default");                         // prints "default"
	echo $Nest->get(["one", "bad"], "default");                // prints "default"
	echo $Nest->get(["bad", "two"], "default");                // prints "default"

	// valid path returns value, not default
	echo $Nest->get(["one", "two"], "default");                // prints "three"

##Setting *(always returns `$this`)*
Setting a value will create the path if it doesn't exist. Warning, you can overwrite a value by extending the path beyond it.

	$Nest->set("foo", "newbar");                               // sets "foo" to "newbar"
	$Nest->set(["one", "two"], "newthree");                    // sets ["one", "two"] to "newthree"
	$Nest->set(["one", "four"], "five");                       // creates ["one", "four"] and sets it to "five"

#Shortcuts
Values can be accessed by using the path as a property name. This even works for nested paths by using `__` (double underscore by default) as depth separator shortcut.

##Getting *(with default value)*
	// use path as property name
	echo $Nest->foo;                                           // prints "bar"
	// calls as function with parameter for default value if chosen one doesn't exist
	echo $Nest->bad("default");                                // prints "default"

	// use path as property name for nested path
	echo $Nest->one__two                                       // prints "three"

	// use path as function name for nested path with default value supplied
	echo $Nest->one__two("default");                           // prints "three"
	echo $Nest->one__bad("default");                           // prints "default"

##Setting
	$Nest->foo = "newbar";                                     // sets "foo" to "newbar"
	$Nest->one__two = "newthree";                              // sets ["one", "two"] to "newthree"
	$Nest->one__four = "five";                                 // creates ["one", "four"] and sets it to "five"

#Object Support
And it works on objects too!

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

	$Nest = new Nest(new Foo());

This works exactly like arrays, but you can use function names in the path. You can even specify function parameters too, but you have to use the `get()` function for those.

	echo $Nest->foo;                                           // returns "bar"
	echo $Nest->one_two                                        // returns "three"
	echo $Nest->one2_two                                       // returns "three"
	echo $Nest->conditionalstring;                             // returns "noparam"
	echo $Nest->get([["conditionalstring",true]])              // returns "withparam"
	echo $Nest->get([["arrayfromfuncwithparams",true],"one"])  // returns "two"

_Note: Trying to set the value when the path contains an object function may produce unexpected results. Go ahead and try it, but have a fire extinguisher nearby._

#Functions
###`__construct()`
`Nest `**`__construct`**`(`_`array|object `**`$data`**`=[], [string `**`$magicSeparator`**`="__"]`_`)`

Create a new instance with the data supplied.

###`get()`  // Get the value at a path
`mixed `**`get`**`(`_`array|string `**`$path`**`=false, [mixed `**`$default`**`=null]`_`)`

Get the value at a path, or the `$default` value if the value you're looking for isn't there.

###`set()`  // Set the value at a path
`$this `**`set`**`(`_`array|string `**`$path`**`=false, [mixed `**`$value`**`=null]`_`)`

Set the value at a path to `$value`.

#Helper Functions

###`data()`  // Get or set the dataset
`mixed `**`data`**`(`_`[`**`$data`**`=[]]`_`)`

If `$data` is supplied then the dataset is overwritten with the new data, otherwise the existing data is returned.

###`minus()` // Decrease a numeric value at the path
`$this `**`minus`**`(`_`array|string `**`$path`**`=false, [int|float `**`$value`**`=1], [int|float `**`$default`**`=0`_`)`

Subtract the numeric value at `$path` by `$value`. If `$default` is specified then the value will be overwritten with `$default` if
it either doesn't exist or is not numeric.

###`plus()` // Increase a numeric value at the path
`$this `**`plus`**`(`_`array|string `**`$path`**`=false, [int|float `**`$value`**`=1], [int|float `**`$default`**`=0`_`)`

Increase the numeric value at `$path` by `$value`. If `$default` is specified then the value will be overwritten with `$default` if
it either doesn't exist or is not numeric.

#Array Functions

###`append()` // Append to an array
`$this `**`plus`**`(`_`array|string `**`$path`**`=false, [mixed `**`$value`**`=null], [bool `**`$force`**`=false`_`)`

Append the supplied `$value` to the array at `$path`. If the value at `$path` isn't an array, `$force` can let you convert it to an array.

###`count()` // Count the items in an array
`$this `**`count`**`(`_`array|string `**`$path`**`=false, [int `**`$default`**`=0]`_`)`

Count the items in the array at `$path` or return the `$default` value if it's not an array.

###`merge()` // Merge the array at `$path` with the supplied array
`$this `**`count`**`(`_`array|string `**`$path`**`=false, [array `**`$value`**`=[]], [bool `**`$force`**`=false`_`)`

`array_merge()` the array at `$path` with `$value`. `$force` will convert the value to an array if it's not one already.

#JSON Functions

###`loadJSON()` // Update dataset to data from a JSON string
`$this `**`loadJSON`**`(`_`string `**`$json`**_`)`

Decode the `$json` and replace the internal dataset with the data.

###`toJSON()` // Update dataset to data from a JSON string
`$this `**`toJSON`**`(`_`string `**`$json`**`, [bool `**`$pretty`**`=true`_`)`

Export the current dataset as JSON. `$pretty` will format the output in a more human-readable manner.