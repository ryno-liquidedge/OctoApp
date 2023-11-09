<?php
namespace LiquidedgeApp\Octoapp\app\app\solid\property_set;

/**
 * Class standard
 * @package app\property_set\intf
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class helper extends \LiquidedgeApp\Octoapp\app\app\intf\standard {

	public static $solid_class_arr = [];

	//--------------------------------------------------------------------------------
	public function __construct() {

		self::$solid_class_arr = \LiquidedgeApp\Octoapp\app\app\solid\property_set\incl\library::$solid_arr;

	}
	//--------------------------------------------------------------------------------
	protected function get_instance_data($key, $options = []) {

		if(!isset(self::$solid_class_arr[$key])) return null;

		return self::$solid_class_arr[$key];

	}
	//--------------------------------------------------------------------------------

    /**
     * @param $key
     * @param array $options
     * @return mixed | \LiquidedgeApp\Octoapp\app\app\solid\property_set\intf\standard
     */
	public function get_instance($key, $options = []) {

		$instance_data = $this->get_instance_data($key, $options);

		if(!$instance_data) return null;

		$instance_data["instance"] = call_user_func([$instance_data["classname"], "make"]);

		return $instance_data ? $instance_data["instance"] : false;

	}
	//--------------------------------------------------------------------------------
}
