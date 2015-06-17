<?php

namespace Hambrook;

/**
 * NEST
 *
 * Easily get and set nested items within arrays and objects without the hassle of validation.
 *
 * @package    Nest
 * @version    1.0.3
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
class Nest implements \Iterator {
	private $data           = [];
	private $magicSeparator = "__";
	private $position       = 0;
	private $keys           = [];
	private $dirty          = true;

	public function __construct($data=[], $magicSeparator="__") {
		$this->data = $data;
		$this->magicSeparator = preg_quote($magicSeparator, "/");
		$dirty = true;
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
	 *
	 * @todo    Add support for function arguments on objects by adding callables to the path array
	 */
	public function get($path=false, $default=null) {
		if (func_num_args() == 0) {
			return $this->data;
		}

		$var = $this->data;
		if (!is_array($var) && !is_object($var)) { return $default; }
		if (!is_array($path)) { $path = [$path]; }
		foreach ($path as $level) {
			if (is_array($level)) {
				// is this level a function name and parameters?
				if (is_object($var) &&  ($func = array_shift($level)) && method_exists($var, $func)) {
					$var = @call_user_func_array([$var, $func], $level);
					continue;
				} else {
					// no? oh well, have this instead.
					return $default;
				}
			} else
			// or an array property?
			if (is_array($var) && array_key_exists($level, $var)) {
				$var = $var[$level];
				continue;
			} else
			// maybe an object property?
			if (is_object($var) && property_exists($var, $level)) {
				$var = $var->$level;
				continue;
			} else
			// how about an object method?
			if (is_object($var) && method_exists($var, $level)) {
				$var = @call_user_func([$var, $level]);
				continue;
			} else {
				// no? oh well, have this instead.
				return $default;
			}
		}

		return $var;
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
	public function set($path, $value=null) {
		if (!is_array($path)) { $path = [$path]; }
		$tmp =& $this->data;
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
					$tmp->$level = new stdClass();
				}
				$tmp =& $tmp->$level;
			}
		}
		if (func_num_args() > 1) {
			$tmp = $value;
		}

		$this->dirty = true;

		return $this;
	}

	/**
	 * DATA
	 *
	 * Get or set the actual data array/object held by this class
	 *
	 * @param   array  $path        Array or object to set as the data
	 *
	 * @return  $this|array|object  The final array or object
	 */
	public function data($data=[]) {
		if (func_num_args()) {
			$this->data = $data;
			$this->dirty = true;
			return $this;
		}
		return $this->data;
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
	 * @return  $this                 Return self, for chaining
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
	 * @return  $this                 Return self, for chaining
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
	public function append($path, $value=null, $force=true) {
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
	 * COUNT
	 *
	 * Count the items at the path
	 *
	 * @param   array|string  $path     String or array of array/object keys to the nested value
	 * @param   int           $default  The amount to return if invalid
	 *
	 * @return  $this                   Return self, for chaining
	 */
	public function count($path, $default=0) {
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
	public function merge($path, $value=[], $force=true) {
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
	 * @return  this           The generated JSON
	 */
	public function loadJSON($json) {
		$this->data = @json_decode($json, true);
		$dirty = true;
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


	/*************************************************************************
	 *  STATIC FUNCTIONS                                                     *
	 *************************************************************************/

	/**
	 * _GET (static)
	 *
	 * Get nested value from array or object without having to check each level
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
			"/".$this->magicSeparator."/",
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
		$this->position = 0;
	}

	/**
	* @internal
	*/
	public function current() {
		$this->cleanIndex();
		$key = $this->keys[$this->position];
		if (is_array($this->data)) {
			return $this->data[$key];
		}
		if (is_object($this->data)) {
			return $this->data->$key;
		}
	}

	/**
	* @internal
	*/
	public function key() {
		return $this->keys[$this->position];
	}

	/**
	* @internal
	*/
	public function next() {
		if (!array_key_exists(++$this->position, $this->keys)) {
			return null;
		}
		$this->keys[$this->position];
	}

	/**
	* @internal
	*/
	public function valid() {
		$this->cleanIndex();
		if (!array_key_exists($this->position, $this->keys)) {
			return false;
		}
		$key = $this->keys[$this->position];
		if (is_array($this->data)) {
			return isset($this->data[$key]);
		}
		if (is_object($this->data)) {
			return isset($this->data->$key);
		}
		return false;
	}

	/**
	* @internal
	*/
	private function cleanIndex() {
		if (!$this->dirty) {
			return true;
		}
		if (is_array($this->data)) {
			$this->keys = array_keys($this->data);
		} else
		if (is_object($this->data)) {
			$this->keys = get_object_vars($this->data);
		} else {
			return $this;
		}
		$this->dirty = false;
		return $this;
	}
}