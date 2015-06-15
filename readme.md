#Nest
######By Rick Hambrook
-----

Nest is a PHP class that lets you easily get and set values in nested arrays and objects without worrying about errors or validation. You don't have to check if keys, properties or functions exist.

Need a value from an array that is returned by a function that requires parameters on an object which is stored in an array? Nest will get it for you, or return a default value if the one you want isn't there. Easy.

##Example

	$Nest = new Nest(
		[
			"foo" => "bar",
			"one" => [
				"two" => "three"
			]
		]
	);

####Use a string as the path parameter

	$value = $Nest->get("foo");
	// "bar"

####We're going two levels in this time, so use an array for the path

	$value = $Nest->get(["one", "two"]);
	// "three"

####What if we try to get something that isn't there? Does it error?

	$value = $Nest->get(["nope", "two"]);
	// returns `null`, not an error

####Or we can specify our own default in case of error

	$value = $Nest->get(["nope", "two"], "safe");
	// returns "safe", not an error

##Who is it for?
Nest is for working with arrays and objects were you aren't always sure of the data. It works great with the `Config` class for storing configuration data for other classes or CLI scripts. But it can be used anywhere.

##Feedback
Tell me if you loved it. Tell me if you hated it. Tell me if you used it and thought "meh". I'm keen to hear your feedback.

##Roadmap
* Add composer support
* Add any other standard documentation that should be included
* Add tests for setting
* A proper test suite, but something nice and compact
* _If you have an idea, [let me know](mailto:rick@rickhambrook.com)._

##Changelog
`2015-06-16` Update license and attach it properly.
`2015-06-15` Initial public release with basic docs.

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