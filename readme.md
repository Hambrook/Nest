#Nest
######By Rick Hambrook
-----

Nest is a PHP class that lets you easily get and set values in nested arrays and objects without worrying about errors or validation. You don't have to check if keys, properties or functions exist.

Need a value from an array that is returned by a function that requires parameters on an object which is stored in an array? Nest will get it for you, or return a default value if the one you want isn't there. Easy.

You can get and set nested values. You can iterate over them with `foreach`. The only limitation is that you can't set the value returned by a function because, well, it's obvious.

####Why do this...
```php
// need to get $array["one"]["two"]
if (array_key_exists("one", $array) && array_key_exists("two", $array["one"])) {
	$value = $array["one"]["two"];
}
```

####When you could do this?
```php
// need to get $array["one"]["two"]
$value = $array->one__two;
```
You don't have to worry about any key checks, or checking if things are set... Just fetch the value. You can specify a default value (`null` by default) to use in case the one you want isn't there. Focus on building great apps instead of validating data.

##Example
```php
$Nest = new \Hambrook\Nest(
	[
		"foo" => "bar",
		"one" => [
			"two" => "three"
		]
	]
);
```

####Use a string as the path parameter
```php
$value = $Nest->get("foo");
// "bar"
```

####We're going two levels in this time, so use an array for the path
```php
$value = $Nest->get(["one", "two"]);
// "three"
```

####What if we try to get something that isn't there? Does it error?
```php
$value = $Nest->get(["nope", "two"]);
// returns `null`, not an error
```

####Or we can specify our own default in case of error
```php
$value = $Nest->get(["nope", "two"], "safe");
// returns "safe", not an error
```

##Who is it for?
Nest is for working with arrays and objects were you aren't always sure of the data. It works great with the [Config](https://github.com/Hambrook/Config) class for storing configuration data for other classes or CLI scripts. But it can be used anywhere.

##Testing
Install PHPUnit globally, then run it on the `tests` directory.

##Feedback
Tell me if you loved it. Tell me if you hated it. Tell me if you used it and thought "meh". I'm keen to hear your feedback.

##Roadmap
* Add composer support
* Add any other standard documentation that should be included
* _If you have an idea, [let me know](mailto:rick@rickhambrook.com)._

##Changelog
* `2015-06-23` fix(set): fix namespacing for stdClass
* `2015-06-22` feat(tests): convert tests to PHPUnit (didn't bother bundling PHPUnit) and create more tests
* `2015-06-18` feat(tests): update tests and 'foreach' tests
* `2015-06-18` fix(foreach): fix foreach iteration not working on objects
* `2015-06-17` feat(docs): make Config keyword a link
* `2015-06-17` feat(docs): update docs formatting to include syntax highlighting on Github
* `2015-06-16` feat(license): update license to GPLv3 and attach it properly
* `2015-06-15` initial public release

##License
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