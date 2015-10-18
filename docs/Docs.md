#Nest Usage
######By Rick Hambrook
-----

Just create new new **Nest()** object and pass it an array or object.

```php
$Nest = new \Hambrook\Nest\Nest(
	[
		"foo" => "bar",
		"one" => [
			"two" => "three"
		]
	]
);
```

Then you can set or get values as much as you want, without having to do validation checks first.

##The Path
Nest uses a `$path` parameter a lot. This is an array of keys, property names or function names to the value you want to work with.
The `$path` may be a string if you're only using one level.

For example, the path to `"bar"` above is `"foo"`, but the path to `"three"` is `["one", "two"]`.

##Getting
The first parameter is the path (an array of keys) to drill down to the value you want. Or just a string if it's only one level down you need.

If the path is invalid or the value isn't there, you'll get back the default value. This is either the second parameter (if you supplied it) or `null`.
```php
// first param can be a string (if getting single depth) or an array
echo $Nest->get("foo");                                    // prints "bar"

// with array path for nested values
$Nest->get("one");                                         // returns ["two" => "three"]

// with array path for nested values
echo $Nest->get(["one", "two"]);                           // prints "three"
```

###Getting with default value (pass as second param) if path is invalid
```php
echo $Nest->get("bad", "default");                         // prints "default"
echo $Nest->get(["one", "bad"], "default");                // prints "default"
echo $Nest->get(["bad", "two"], "default");                // prints "default"

// valid path returns value, not default
echo $Nest->get(["one", "two"], "default");                // prints "three"
```

##Setting *(always returns `$this`)*
Setting a value will create the path if it doesn't exist. Warning, you can overwrite a value by extending the path beyond it.

```php
$Nest->set("foo", "newbar");                               // sets "foo" to "newbar"
$Nest->set(["one", "two"], "newthree");                    // sets ["one", "two"] to "newthree"
$Nest->set(["one", "four"], "five");                       // creates ["one", "four"] and sets it to "five"
```

#Shortcuts
Values can be accessed by using the path as a property name. This even works for nested paths by using `__` (double underscore by default) as depth separator shortcut.

##Getting *(with default value)*
```php
// use path as property name
echo $Nest->foo;                                           // prints "bar"
// calls as function with parameter for default value if chosen one doesn't exist
echo $Nest->bad("default");                                // prints "default"

// use path as property name for nested path
echo $Nest->one__two                                       // prints "three"

// use path as function name for nested path with default value supplied
echo $Nest->one__two("default");                           // prints "three"
echo $Nest->one__bad("default");                           // prints "default"
```

##Setting
```php
$Nest->foo = "newbar";                                     // sets "foo" to "newbar"
$Nest->one__two = "newthree";                              // sets ["one", "two"] to "newthree"
$Nest->one__four = "five";                                 // creates ["one", "four"] and sets it to "five"
```

#Object Support
And it works on objects too!
```php
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
	function data() {
		return "data from inside object";
	}
}

$Nest = new \Hambrook\Nest\Nest(new Foo());
```

This works exactly like arrays, but you can use function names in the path. You can even specify function parameters too, but you have to use the `get()` function for those.

```php
echo $Nest->foo;                                           // returns "bar"
echo $Nest->one_two                                        // returns "three"
echo $Nest->one2_two                                       // returns "three"
echo $Nest->conditionalstring;                             // returns "noparam"
echo $Nest->get([["conditionalstring",true]])              // returns "withparam"
echo $Nest->get([["arrayfromfuncwithparams",true],"one"])  // returns "two"
```

_Note: Trying to set the value when the path contains an object function may produce unexpected results. Go ahead and try it, but have a fire extinguisher nearby._

##Avoiding collisions with helper functions
Nest has lots of helper functions, so if you've stored an object inside Nest and don't want Nest's helper functions to collide with the objects functions while using shortcut paths, you can prefix your shortcut paths with the separator... `__` (double underscore by default)
```php
$Nest = new \Hambrook\Nest\Nest(new Foo());
var_dump($Nest->data);                                     // object(Foo)...
var_dump($Nest->__data);                                   // string(23) "data from inside object"
var_dump($Nest->get("data"));                              // string(23) "data from inside object"
```

#Functions
###`__construct()`
`Nest `**`__construct`**`(`*`array|object `**`$data`**`=[], string `**`$magicSeparator`**`="__"`*`)`

Create a new instance with the data supplied.

###`get()`  // Get the value at a path
`mixed `**`get`**`(`_`array|string `**`$path`**`=false, mixed `**`$default`**`=null, bool `**`$isSetCheck`**`=false`_`)`

Get the value at a path, or the `$default` value if the value you're looking for isn't there. Optionally pass `true` for `$isSetCheck` to simply check if the value exists.

###`set()`  // Set the value at a path
`$this `**`set`**`(`_`array|string `**`$path`**`=false, mixed `**`$value`**`=null`_`)`

Set the value at a path to `$value`.

#Helper Functions

###`data()`  // Get or set the dataset
`mixed `**`data`**`(`_`array|object `**`$data`**`=[]`_`)`

If `$data` is supplied then the dataset is overwritten with the new data, otherwise the existing data is returned.

###`minus()` // Decrease a numeric value at the path
`$this `**`minus`**`(`_`array|string `**`$path`**`=false, int|float `**`$value`**`=1, int|float `**`$default`**`=0`_`)`

Subtract the numeric value at `$path` by `$value`. If `$default` is specified then the value will be overwritten with `$default` if
it either doesn't exist or is not numeric.

###`plus()` // Increase a numeric value at the path
`$this `**`plus`**`(`_`array|string `**`$path`**`=false, int|float `**`$value`**`=1, int|float `**`$default`**`=0`_`)`

Increase the numeric value at `$path` by `$value`. If `$default` is specified then the value will be overwritten with `$default` if
it either doesn't exist or is not numeric.

#Array Functions

###`append()` // Append to an array
`$this `**`append`**`(`_`array|string `**`$path`**`=false, mixed `**`$value`**`=null, bool `**`$force`**`=false`_`)`

Append the supplied `$value` to the array at `$path`. If the value at `$path` isn't an array, `$force` can let you convert it to an array.

###`count()` // Count the items in an array
`int `**`count`**`(`_`array|string `**`$path`**`=false, int `**`$default`**`=0`_`)`

Count the items in the array at `$path` or return the `$default` value if it's not an array.

###`merge()` // Merge the array at `$path` with the supplied array
`$this `**`merge`**`(`_`array|string `**`$path`**`=false, array `**`$value`**`=[], bool `**`$force`**`=false`_`)`

`array_merge()` the array at `$path` with `$value`. `$force` will convert the value to an array if it's not one already.

#JSON Functions

###`loadJSON()` // Update dataset to data from a JSON string
`$this `**`loadJSON`**`(string `**`$json`**`)`

Decode the `$json` and replace the internal dataset with the data.

###`toJSON()` // Encode the data to JSON and return it
`string `**`toJSON`**`(`_`bool `**`$pretty`**`=true`_`)`

Export the current dataset as a JSON string. `$pretty` will format the output in a more human-readable manner.

#Unit Testing
There a numerous tests built for the [PHPUnit](https://phpunit.de) testing package. You will need to install PHPUnit globally...
```bash
wget https://phar.phpunit.de/phpunit.phar
chmod +x phpunit.phar
sudo mv phpunit.phar /usr/local/bin/phpunit
```
then run it on the `tests/` directory.
```bash
phpunit path/to/Nest/tests/
```

#License
Copyright &copy; 2015 Rick Hambrook

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
