<?php

namespace octoapi\core\com\arr;

/**
 * Library of functions related to using arrays.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class arr {
	//--------------------------------------------------------------------------------
    // static
    //--------------------------------------------------------------------------------
    /**
     * @param $add_to_arr
     * @param $add_from_arr
     * @return mixed
     */
	public static function add($add_to_arr, $add_from_arr) {
		// params
		$add_from_arr = self::splat($add_from_arr);

		// add elements to source array
		foreach ($add_from_arr as $add_from_item) {
			$add_to_arr[] = $add_from_item;
		}

		// done
		return $add_to_arr;
	}
    //--------------------------------------------------------------------------------

    /**
     * @param $var
     * @param array $options
     * @return array|false|string[]
     */
	public static function splat($var, $options = []) {
		// options
		$options = array_merge([
			"delimiter" => false,
		], $options);

		// check if we have an array
		if (is_array($var)) return $var;

		// check if value is false
		if ($var === false) return [];

		// delimiter
		if ($options["delimiter"] !== false) {
			return explode($options["delimiter"], $var);
		}

		// done
		return [$var];
	}
    //--------------------------------------------------------------------------------
	/**
	 * Returns the first value of an array.
	 *
	 * @param array $arr <p>The array.</p>
	 *
	 * @return mixed|boolean <p>The first value or boolean(false) if the array is empty.</p>
	 */
    public static function get_first_value($arr) {
		// params
    	$arr = self::splat($arr);

		// return the first value
        foreach ($arr as $arr_item) {
			return $arr_item;
		}

		// done
        return false;
    }
    //--------------------------------------------------------------------------------
	/**
	 * Returns the first index of an array.
	 *
	 * @param array $arr <p>The array.</p>
	 *
	 * @return mixed|boolean <p>The first index or boolean(false) if the array is empty.</p>
	 */
    public static function get_first_index($arr) {
		// params
    	$arr = self::splat($arr);

		// return the first index
        foreach ($arr as $arr_index => $arr_item) {
			return $arr_index;
		}

		// done
        return false;
    }
    //--------------------------------------------------------------------------------
	/**
	 * Trims all items in the array recursively. This function changes the input array
	 * and returns nothing.
	 *
	 * @param array $arr <p>The array.</p>
	 */
	public static function trim(&$arr) {
		// loop each item
		foreach ($arr as $index => $item) {
			// recurse function if we have another array, else just trim the item
			if (is_array($item)) self::trim($arr[$index]);
			else $arr[$index] = trim($item);
		}
	}
    //--------------------------------------------------------------------------------
	public static function has_signature_items($signature_prefix, $options = []) {
		// options
		$options = array_merge([
		], $options);

		// find an item starting with the signature prefix
		$preg_signature_prefix = preg_quote($signature_prefix);
		foreach ($options as $option_index => $option_item) {
			// check signature
			if (preg_match("/^{$preg_signature_prefix}/i", $option_index)) {
				if ($option_item !== false) return true;
			}
		}

		// done
		return false;
	}
    //--------------------------------------------------------------------------------
	public static function extract_signature_items($signature, $options = []) {
		// options
		$options = array_merge([
		], $options);

		// find and save the items with the matching signature
		$item_arr = [];
		$signature_size = strlen($signature);
		foreach ($options as $option_index => $option_item) {
			// skip null
			if ($option_item === null) continue;

			// signature
			$item_signature = substr($option_index, 0, $signature_size);
			$item_index = substr($option_index, 1);

			// we only care for the specified signature
			if ($item_signature != $signature) continue;

			// build item without signature
			$item_arr[$item_index] = $option_item;
		}

		// done
		return $item_arr;
	}
    //--------------------------------------------------------------------------------
	public static function group($arr, $fn_group) {
		$result_arr = [];

		foreach ($arr as $arr_item) {
			$result_index = $fn_group($arr_item);
			if (!isset($result_arr[$result_index])) $result_arr[$result_index] = [];
			$result_arr[$result_index][] = $arr_item;
		}

		return $result_arr;
	}
	//--------------------------------------------------------------------------
	public static function extract_from_arr($arr, $key, $options = []){

		$options = array_merge([
		    "default" => []
		], $options);

		if(isset($arr[$key])) return $arr[$key];

		return $options["default"];
	}
    //--------------------------------------------------------------------------------
}