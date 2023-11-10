<?php

namespace LiquidedgeApp\Octoapp\app\app\arr;

/**
 * Library of functions related to using arrays.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class arr extends \com\arr{
	//--------------------------------------------------------------------------------
    // static
    //--------------------------------------------------------------------------------
    /**
     * splits an array into to arrays
     * @param array $arr
     * @return array
     */
    public static function split($arr = []) {

        //return empty
        if(!$arr) return [[], []];

        //return chunk list
        return array_chunk($arr, ceil(sizeof($arr) / 2));
    }
    //--------------------------------------------------------------------------------

	/**
	 * Standard range method with the option of formatting the value via an anonymous function
	 * @param $start
	 * @param $end
	 * @param array $options
	 * @return array
	 */
	public static function range($start, $end, $options = []) {

    	$options = array_merge([
    	    "fn" => false
    	], $options);

    	$range = range($start,$end);
		$arr = array_combine($range, $range);

		if($options["fn"] && is_callable($options["fn"])){
			array_walk($arr, $options["fn"]);
		}

		return $arr;
	}
    //--------------------------------------------------------------------------------
    /**
     * @param $class
     * @param array $arr
     * @return bool
     */
    public static function arr_contains_signature_item($class, $arr = []) {

	    $arr = array_merge([
	        "signature" => "."
	    ], $arr);

	    $class_arr = self::extract_signature_items($arr["signature"], $arr);

        if(substr($class, 0, 1) == $arr["signature"])
            $class = substr($class, 1, strlen($class));

	    foreach ($class_arr as $class_name => $is_enabled){
	        if(strpos($class_name, $class) !== false) {
                return true;
            }
        }
	    return false;
    }
    //--------------------------------------------------------------------------------

    /**
     * Removes the first index in an array
     * @param $arr
     * @return mixed
     */
	public static function unset_first_index(&$arr) {
		$index = self::get_first_index($arr);
		unset($arr[$index]);
		return $arr;
	}
	//--------------------------------------------------------------------------------

	/**
	 * Extracts the first index in an array
	 * @param $arr
	 * @return int|string|null
	 */
    public static function get_last_index($arr) {
		// params
    	$arr = self::splat($arr);

		return key(array_slice($arr, -1, 1, true));
    }
	//--------------------------------------------------------------------------------
	/**
	 * Takes a 2 dimensional array and builds a 1 dimensional list from it
	 * EG:
	 * 	$arr = [
	 * 		0 => [
	 * 			'id' => 254,
	 * 			'name' => 'Timmy',
	 * 			'surname' => 'Tom',
	 * 			'email' => 'timmy@gmail.com',
	 * 		],
	 * 		1 => [
	 * 			'id' => 287,
	 * 			'name' => 'Tommy',
	 * 			'surname' => 'Tim',
	 * 			'email' => 'tom@gmail.com',
	 * 		]
	 * 	];
	 *
	 * 	$result_list = \app\arr::arr_to_list($arr, 'id', 'email');
	 * 	$result_list = [
	 * 		254 => 'timmy@gmail.com',
	 * 		287 => 'tom@gmail.com',
	 * 	];
	 *
	 * @param $arr
	 * @param $column_key
	 * @param $column_value
	 * @return array
	 */
    public static function arr_to_list($arr, $column_key, $column_value) {
		return array_filter(
			array_combine(
				array_column($arr, $column_key),
				array_column($arr, $column_value)
			)
		);
    }
	//--------------------------------------------------------------------------------

	/**
	 * Extracts items from an array based on the first char
	 *
	 * EG:
	 * $arr = [
	 *		'.btn' => true,
	 * 		'.btn-primary' => true,
	 * 		'#color' => 'red',
	 * ];
	 *
	 * this will extract all items starting with '.'
	 * $result_list = \app\arr::extract_signature_items('.', $arr);
	 *
	 * @param $signature
	 * @param array $options
	 * @return array
	 */
	public static function extract_signature_items($signature, $options = []) {
		// options
		$options = array_merge([
			"remove_signature" => true
		], $options);

		// find and save the items with the matching signature
		$item_arr = [];
		$signature_size = strlen($signature);
		foreach ($options as $option_index => $option_item) {
			// signature
			$item_signature = substr($option_index, 0, $signature_size);
			$item_index = substr($option_index, 1);

			// we only care for the specified signature
			if ($item_signature != $signature) continue;

			// build item without signature
			if($options["remove_signature"]) $item_arr[$item_index] = $option_item;
			else $item_arr[$option_index] = $option_item;
		}

		// done
		return $item_arr;
	}
	//--------------------------------------------------------------------------
	public static function extract_from_arr($arr, $key, $options = []){

		$options = array_merge([
		    "default" => []
		], $options);

		if(is_object($arr)) $arr = (array) $arr;

		$arr = self::splat($arr);

		if(isset($arr[$key])) return $arr[$key];

		return $options["default"];
	}
    //--------------------------------------------------------------------------------
	public static function count_consecutive_sequenced_entries($arr) {

		$result = array();
		$prev_value = array('value' => null, 'amount' => null);

		foreach ($arr as $val) {
			if ($prev_value['value'] != $val) {
				unset($prev_value);
				$prev_value = array('value' => $val, 'amount' => 0);
				$result[] =& $prev_value;
			}

			$prev_value['amount']++;
		}

		return $result;
	}
    //--------------------------------------------------------------------------------
}