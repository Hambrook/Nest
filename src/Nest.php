<?php

namespace Hambrook;

/**
 * NEST
 *
 * Easily get and set nested items within arrays and objects without the hassle of validation.
 *
 * @package    Nest
 *
 * @version    1.2.2
 *
 * @author     Rick Hambrook <rick@rickhambrook.com>
 * @copyright  2015 Rick Hambrook
 * @license    https://www.gnu.org/licenses/gpl.txt  GNU General Public License v3
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
class Nest extends \ArrayObject {

	/**
	 * @var  array  Scope all of this inside one variable to minimise collisions
	 */
	private $_ = [
		"data"           => [],
		"magicSeparator" => "__",
		"position"       => 0,
		"keys"           => [],
		"dirty"          => true
	];

	/**
	 * __CONSTRUCT
	 *
	 * @param  array   $data            Array or object to set as the data
	 * @param  string  $magicSeparator  String to separate path levels for magic methods
	 */
	public function __construct($data=[], $magicSeparator="__") {
		$this->_["data"]            = $data;
		$this->_["magicSeparator"]  = preg_quote($magicSeparator, "/");
		$this->_["dirty"]           = true;
	}

	/**
	 * GET
	 *
	 * Get nested value from array or object without having to check each level
	 *
	 * @param   array|string  $path     String or array of array/object keys to the nested value
	 * @param   mixed         $default  The value to return if the requested value doesn't exist
	 *
	 * @return  mixed                   The value at the specified path, or the default if not found
	 */
	public function get($path=false, $default=null, $isSetCheck=false) {
		if (func_num_args() == 0 || $path === false) {
			return ($isSetCheck) ? isset($this->_["data"]) : $this->_["data"];
		}

		if ($isSetCheck) {
			$default = false;
		}

		$var = $this->_["data"];
		if (!is_array($var) && !is_object($var)) { return $default; }
		if (!is_array($path)) { $path = [$path]; }
		foreach ($path as $level) {
			if (is_array($level)) {
				// is this level a function name and parameters?
				if (is_object($var) &&  ($func = array_shift($level)) && is_callable([$var, $func])) {
					$var = @call_user_func_array([$var, $func], $level);
					continue;
				}
			} else
			// or an array property?
			if (is_array($var) && array_key_exists($level, $var)) {
				$var = $var[$level];
				continue;
			} else
			// maybe an object property?
			if (is_object($var)) {
				if (property_exists($var, $level)) {
					$var = $var->$level;
					continue;
				} else
				// how about an object method?
				if (is_callable([$var, $level])) {
					$var = @call_user_func([$var, $level]);
					continue;
				}
			}
			// no? oh well, have this instead.
			return $default;
		}

		return ($isSetCheck) ?: $var;
	}

	/**
	 * SET
	 *
	 * Set a nested value in an array or object without having to check each level
	 *
	 * @param   array|string  $path   String or array of array/object keys to the nested value
	 * @param   mixed         $value  The new value to use
	 *
	 * @return  $this                 Return self, for chaining
	 */
	public function set($path=false, $value=null) {
		if ($path === false) {
			$this->_["data"] = $value;
			return $this;
		}
		if (!is_array($path)) { $path = [$path]; }
		$tmp =& $this->_["data"];
		foreach ($path as $level) {
			if (!is_array($tmp) && !is_object($tmp)) {
				$tmp = [];
			}
			if (is_array($tmp)) {
				if (!isset($tmp[$level])) {
					$tmp[$level] = [];
				}
				$tmp =& $tmp[$level];
			} else
			if (is_object($tmp)) {
				if (!isset($tmp->$level)) {
					$tmp->$level = new \stdClass();
				}
				$tmp =& $tmp->$level;
			}
		}
		if (func_num_args() > 1) {
			$tmp = $value;
		}

		$this->_["dirty"] = true;

		return $this;
	}

	/**
	 * EXISTS
	 *
	 * Determine if a value is set or not
	 *
	 * @param   array|string  $path  String or array of array/object keys to the nested value
	 *
	 * @return  bool                 Whether or not the value is set
	 */
	public function exists($path=false) {
		return $this->get($path, false, true);
	}

	/**
	 * UNSET
	 *
	 * Determine if a value is set or not
	 *
	 * @param   array|string  $path  String or array of array/object keys to the nested value
	 *
	 * @return  $this                This
	 */
	public function delete($path=false) {
		if (!is_array($path)) {
			$path = [$path];
		}
		// Is there nothing to unset?
		if (!$this->exists($path)) { return $this; }
		$key = array_pop($path);
		if (!$this->exists($path)) { return $this; }
		$tmp = $this->get($path);
		if (is_array($tmp)) {
			unset($tmp[$key]);
		}
		if (is_object($tmp)) {
			unset($tmp->$key);
		}
		$this->set($path, $tmp);
		return $this;
	}
	public function remove($path=false) {
		return $this->delete($path);
	}

	/**
	 * DATA
	 *
	 * Get or set the actual data array/object held by this class
	 *
	 * @param   array  $data        Array or object to set as the data
	 *
	 * @return  $this|array|object  The final array or object
	 */
	public function data($data=[]) {
		if (func_num_args()) {
			$this->_["data"]  = $data;
			$this->_["dirty"] = true;
			return $this;
		}
		return $this->_["data"];
	}

	/**
	 * KEYS
	 *
	 * Get a list of valid keys for the dataset
	 *
	 * @param   array  $path  Path to the data you want keys for
	 *
	 * @return  array         Array of keys for data at the path
	 */
	public function keys($path=false) {
		$data = $this->get($path);
		if (!is_array($data)) {
			return [];
		}
		return array_keys($data);
	}


	/*************************************************************************
	 *  NUMERIC FUNCTIONS                                                    *
	 *************************************************************************/

	/**
	 * PLUS
	 *
	 * Increment the numerical value at the specified path, by the specified amount
	 *
	 * @param   array|string  $path     String or array of array/object keys to the nested value
	 * @param   float         $value    The amount to increment by
	 * @param   bool          $force    Force the value to be numeric, even if it's not
	 * @param   float         $default  Default value to start with if existing value isn't numeric
	 *
	 * @return  $this                   Return self, for chaining
	 */
	public function plus($path, $value=1, $default=0) {
		$tmp = $this->get($path);
		if (!is_numeric($tmp)) {
			if (func_num_args() > 2) {
				return $this;
			}
			$tmp = $default;
		}
		$tmp = $tmp + $value;
		$this->set($path, $tmp);

		return $this;
	}

	/**
	 * MINUS
	 *
	 * Decrease the numerical value at the specified path, by the specified amount
	 *
	 * @param   array|string  $path     String or array of array/object keys to the nested value
	 * @param   float         $value    The amount to decrease by
	 * @param   bool          $force    Force the value to be numeric, even if it's not
	 * @param   float         $default  Default value to start with if existing value isn't numeric
	 *
	 * @return  $this                   Return self, for chaining
	 */
	public function minus($path, $value=1, $default=0) {
		$tmp = $this->get($path);
		if (!is_numeric($tmp)) {
			if (func_num_args() > 2) {
				return $this;
			}
			$tmp = $default;
		}
		$tmp = $tmp - $value;
		$this->set($path, $tmp);

		return $this;
	}


	/*************************************************************************
	 *  ARRAY FUNCTIONS                                                      *
	 *************************************************************************/

	/**
	 * APPEND
	 *
	 * Append data (arrays only at present)
	 *
	 * @param   array|string  $path   The path to the nested array to append to
	 * @param   mixed         $value  New new value to append
	 * @param   mixed         $force  Force the value to be an array, even if it's not
	 *
	 * @return  $this                 Return self, for chaining
	 */
	public function append($path=false, $value=null, $force=true) {
		$tmp = $this->get($path);
		if (!is_array($tmp)) {
			if (!$force) {
				return $this;
			}
			$tmp = [];
		}
		$tmp[] = $value;
		$this->set($path, $tmp);

		return $this;
	}

	/**
	 * SORT
	 *
	 * Sort an array by sort method
	 *
	 * @param   array|string     $path             The path to the nested array sort
	 * @param   string           $method           Optional sort method
	 * @param   callable|string  $flagsOrCallable  Optional flags or callback
	 *
	 * @return  $this                              Return self, for chaining
	 */
	public function sort($path=false, $method="", $flagsOrCallable=false) {
		$data = $this->get($path);
		$tmp = &$data;

		if (!is_array($tmp)) {
			return $this;
		}

		if (!is_callable($method)) {
			$method = $method."sort";
		}
		if (!is_callable($method)) {
			return $this;
		}

		if ($flagsOrCallable) {
			call_user_func($method, $tmp, $flagsOrCallable);
		} else {
			call_user_func($method, $tmp);
		}

		$this->set($path, $tmp);
		return $this;
	}

	/**
	 * COUNT
	 *
	 * Count the items at the path
	 *
	 * @param   array|string  $path     String or array of array/object keys to the nested value
	 * @param   int           $default  The amount to return if invalid
	 *
	 * @return  $this                   Return self, for chaining
	 */
	public function count($path=false, $default=0) {
		$tmp = $this->get($path);
		if (!is_array($tmp)) {
			return $default;
		}

		return count($tmp);
	}

	/**
	 * MERGE
	 *
	 * Merge data (arrays only at present)
	 *
	 * @param   array|string  $path   The path to the nested array to merge
	 * @param   array         $value  New new array to merge in
	 * @param   bool          $force  Force the value to be an array, even if it's not
	 *
	 * @return  $this                 Return self, for chaining
	 */
	public function merge($path=false, $value=[], $force=true) {
		$tmp = $this->get($path);
		if (!is_array($value) || !count($value)) {
			return $this;
		}
		if (!is_array($tmp)) {
			if (!$force) {
				return $this;
			}
			$tmp = [];
		}
		$tmp = array_merge($tmp, $value);
		$this->set($path, $tmp);

		return $this;
	}

	/**
	 * GETITERATOR
	 *
	 * Get an array iterator based on the data
	 *
	 * @return  ArrayIterator  Array iterator of the data
	 */
	public function getIterator() {
		return new \ArrayIterator($this->_["data"]);
    }


	/*************************************************************************
	 *  JSON FUNCTIONS                                                       *
	 *************************************************************************/

	/**
	 * LOADJSON
	 *
	 * Generate JSON and return it
	 *
	 * @param   string  $json  The JSON string to decode and load
	 *
	 * @return  $this          This
	 */
	public function loadJSON($json) {
		$this->data(@json_decode($json, true));
		$this->_["dirty"] = true;
		return $this;
	}

	/**
	 * TOJSON
	 *
	 * Generate JSON and return it
	 *
	 * @return  string  The generated JSON
	 */
	public function toJSON($pretty=true) {
		return json_encode($this->data(), ($pretty) ? JSON_PRETTY_PRINT : 0);
	}


	/*************************************************************************
	 *  MAGIC FUNCTIONS                                                      *
	 *************************************************************************/

	/**
	 * __CALL
	 *
	 * Magic call method
	 *
	 * @param   array|string  $path     String or array of array/object keys to the nested value
	 * @param   mixed         $default  The value to return if the requested value doesn't exist
	 *
	 * @return  mixed                   The value at the specified path, or the default if not found
	 */
	public function __call($path=false, $params=[]) {
		if (count($params)) {
			return $this->get(
				$this->_convertStringToPath($path),
				current($params)
			);
		}
		return $this->get(
			$this->_convertStringToPath($path)
		);
	}

	/**
	 * __GET
	 *
	 * Magic get method
	 *
	 * @param   string  $path  String or array of array/object keys to the nested value
	 *
	 * @return  mixed          The value at the specified path, or the default if not found
	 */
	public function __get($path=false) {
		return $this->get(
			$this->_convertStringToPath($path)
		);
	}

	/**
	 * __SET
	 *
	 * Magic set method
	 *
	 * @param   array|string  $path     String or array of array/object keys to the nested value
	 * @param   mixed         $default  The value to return if the requested value doesn't exist
	 *
	 * @return  mixed                   The value at the specified path, or the default if not found
	 */
	public function __set($path=false, $value=null) {
		return $this->set(
			$this->_convertStringToPath($path),
			$value
		);
	}

	/**
	 * __ISSET
	 *
	 * Magic isset method
	 *
	 * @param   array|string  $path  String or array of array/object keys to the nested value
	 *
	 * @return  bool                 Whether or not the value is set
	 */
	public function __isset($path=false) {
		return $this->exists($this->_convertStringToPath($path));
	}

	/**
	 * __UNSET
	 *
	 * Magic unset method
	 *
	 * @param   array|string  $path  String or array of array/object keys to the nested value
	 *
	 * @return  $this                This
	 */
	public function __unset($path=false) {
		return $this->delete($this->_convertStringToPath($path));
	}


	/*************************************************************************
	 *  STATIC FUNCTIONS                                                     *
	 *************************************************************************/

	/**
	 * _GET (static)
	 *
	 * Get nested value from array or object without having to check each level
	 *
	 * @static
	 *
	 * @param   array|object  $var      The array or object to fetch data from
	 * @param   array|string  $path     String or array of array/object keys to the nested value
	 * @param   mixed         $default  The value to return if the requested value doesn't exist
	 *
	 * @return  mixed                   The value at the specified path, or the default if not found
	 */
	public static function _get() {
		$args = func_get_args();
		$tmp = new Nest(array_shift($args));
		return call_user_func_array([$tmp, "get"], $args);
	}

	/**
	 * _SET (static)
	 *
	 * Get nested value from array or object without having to check each level
	 *
	 * @static
	 *
	 * @param   array|object  $var    The array or object to fetch data from
	 * @param   array|string  $path   String or array of array/object keys to the nested value
	 * @param   mixed         $value  The new value to use
	 *
	 * @return  array|object          The original var, but with updated data
	 */
	public static function _set() {
		$args = func_get_args();
		$tmp = new Nest(array_shift($args));
		call_user_func_array([$tmp, "set"], $args);
		return $tmp->data();
	}


	/*************************************************************************
	 *  HELPER FUNCTIONS                                                     *
	 *************************************************************************/

	/**
	 * _CONVERTSTRINGTOPATH
	 *
	 * Get nested value from array or object without having to check each level
	 *
	 * @param   string  $path  The string to split into a path
	 *
	 * @return  array          The value at the specified path, or the default if not found
	 */
	public function _convertStringToPath($path) {
		return preg_split(
			"/".$this->_["magicSeparator"]."/",
			ltrim($path, "_"),
			NULL,
			PREG_SPLIT_NO_EMPTY
		);
	}


	/*************************************************************************
	 *  ITERATOR FUNCTIONS                                                   *
	 *************************************************************************/

	/**
	* @internal
	*/
	public function rewind() {
		$this->_["position"] = 0;
	}

	/**
	* @internal
	*/
	public function current() {
		$this->_cleanIndex();
		return $this->get($this->_["keys"][$this->_["position"]]);
	}

	/**
	* @internal
	*/
	public function key() {
		return $this->_["keys"][$this->_["position"]];
	}

	/**
	* @internal
	*/
	public function next() {
		if (!array_key_exists(++$this->_["position"], $this->_["keys"])) {
			return null;
		}
		$this->_["keys"][$this->_["position"]];
	}

	/**
	* @internal
	*/
	public function valid() {
		$this->_cleanIndex();
		if (!array_key_exists($this->_["position"], $this->_["keys"])) {
			return false;
		}
		$key = $this->_["keys"][$this->_["position"]];
		if (is_array($this->_["data"])) {
			return isset($this->_["data"][$key]);
		}
		if (is_object($this->_["data"])) {
			return isset($this->_["data"]->$key);
		}
		return false;
	}

	/**
	* @internal
	*/
	private function _cleanIndex() {
		if (!$this->_["dirty"]) {
			return true;
		}
		if (is_array($this->_["data"])) {
			$this->_["keys"] = array_keys($this->_["data"]);
		} else
		if (is_object($this->_["data"])) {
			$this->_["keys"] = array_keys(get_object_vars($this->_["data"]));
		} else {
			return $this;
		}
		$this->_["dirty"] = false;
		return $this;
	}
}
