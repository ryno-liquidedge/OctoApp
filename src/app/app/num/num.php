<?php

namespace LiquidedgeApp\Octoapp\app\app\num;

/**
 * This class contains number helper functions.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class num extends \com\num {
	//--------------------------------------------------------------------------------
  	//  properties
	//--------------------------------------------------------------------------------
    public static function fn_range_list($start = 1000, $end = 50000000, $options = []) {

    	$options = array_merge([
    	    "fn_value" => function($value, $key){
                return $value;
            },
    	    "step_arr" => [
    	    	1 => 1,
    	    	2 => 10,
				3 => 100,
				4 => 1500,
				5 => 15000,
				6 => 150000,
				7 => 1000000,
				8 => 2500000,
				9 => 25000000,
				10 => 250000000,
			]
    	], $options);

		$price_range = [];
		$step_arr = $options["step_arr"];

		$step = isset($step_arr[strlen($start)]) ? $step_arr[strlen($start)] : reset($step_arr);
		for ($i = $start; $i <= $end; $i = $i+$step) {
		    $step = $step_arr[strlen($i)];
		    $price_range[$i] = $options["fn_value"]($i, $i);
		}

		return $price_range;
	}
	//--------------------------------------------------------------------------------
    public static function range($start, $end, $step = 1, $options = []) {

        $options = array_merge([
            "fn_value" => function($value, $key){
                return $value;
            }
        ], $options);

        $return_arr = [];
        $range_arr = range($start, $end, $step);

        foreach ($range_arr as $key => $value){
            $return_arr[$value] = $options["fn_value"]($value, $key);
        }

        return $return_arr;

    }
    //--------------------------------------------------------------------------------
    public static function currency($value, $options = []) {
		// options
		$options = array_merge([
			"include_symbol" => true,
			"decimals" => \core::$app->get_instance()->get_option("app.currency.remove.decimals") ? 0 : 2,
			"has_decimals" => !\core::$app->get_instance()->get_option("app.currency.remove.decimals"),
			"trim" => 0,
		], $options);

		// done
        return parent::currency($value, $options);
    }

	//--------------------------------------------------------------------------------
	public static function num_to_ordinal($number) {
		$ends = array('th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th');
		if ((($number % 100) >= 11) && (($number % 100) <= 13))
			return $number . 'th';
		else
			return $number . $ends[$number % 10];
	}
	//--------------------------------------------------------------------------------
}