<?php

namespace LiquidedgeApp\Octoapp\app\app\solid\property_set\factory;

/**
 * @package app\property_set\factory
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class constant_builder extends \LiquidedgeApp\Octoapp\app\app\intf\runnable {

	protected $constant_arr = [];

	//--------------------------------------------------------------------------------
	public function run($options = []) {
		$this->build_constants();

		if(\core::$app->get_session()) \core::$app->get_session()->solid_class_arr = false;
	}

	//--------------------------------------------------------------------------------
	private function get_constants_arr_string() {
		$return_arr = [];
		$constant_arr = $this->get_constants_arr();

		foreach ($constant_arr as $foldername => $data_arr){

			$return_arr[] = <<<EOD
//-------------------------------------------------------------
//$foldername
//-------------------------------------------------------------
EOD;

			foreach ($data_arr as $key => $data){
				$instance = $data["instance"];
				$return_arr[] = "if(!defined(\"{$instance->get_code()}\")) define(\"{$instance->get_code()}\", \"{$instance->get_key()}\");";

			}

		}

		return $return_arr;

	}

	//--------------------------------------------------------------------------------
	public function get_constants_arr() {

		if($this->constant_arr) return $this->constant_arr;

		$return_arr = [];
		$fn_load_folder = function($folder) use(&$return_arr){
			$foldername = basename($folder);
			foreach (glob("$folder/*") as $filename) {
			    if(!is_file($filename)) continue;
				$class_name = $this->get_class_name_from_filename($filename);
				$instance = call_user_func([$class_name, "make"]);
				$return_arr[$foldername][$instance->get_key()] = [
					"instance" => $instance,
					"classname" => $class_name,
					"key" => $instance->get_key(),
					"code" => $instance->get_code(),
				];
			}
		};

		//first load local set
		$glob_arr = glob(__DIR__."../solid_classes/*");
		foreach ($glob_arr as $folder) $fn_load_folder($folder);

		//load app versions
		$glob_arr = glob(\core::$folders->get_app_app() . "/solid/property_set/solid_classes/*");
		foreach ($glob_arr as $folder) $fn_load_folder($folder);

		return $this->constant_arr = $return_arr;

	}

	//--------------------------------------------------------------------------------
	public function get_class_name_from_filename($filename) {
		$basename = basename($filename);
		return "\\" . str_replace(".", "\\", str_replace(".php", "", $basename));
	}

	//--------------------------------------------------------------------------------
	private function build_constants() {

		$constants_arr_string = $this->get_constants_arr_string();

		if ($constants_arr_string) {
			$dir = \core::$folders->get_app_app() . "/solid/property_set/incl";
			$filename = "app.solid.property_set.incl.constants.php";

			\LiquidedgeApp\Octoapp\app\app\os\os::mkdir($dir);

			$constant_str = implode("\n", $constants_arr_string);

			$content = <<<EOD
<?php
/**
 * @package app\property_set\incl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

$constant_str


EOD;

			file_put_contents("$dir/$filename", $content);
		}

	}
	//--------------------------------------------------------------------------------

	/**
	 * @param $key
	 * @return mixed | \LiquidedgeApp\Octoapp\app\app\solid\property_set\intf\standard
	 */
	public function get_instance($key) {

		$found_data = [];

		foreach ($this->get_constants_arr() as $folder => $data_arr){
			array_filter($data_arr, function($data, $index) use(&$found_data, $key){
				if($index == $key) $found_data = $data;
			}, ARRAY_FILTER_USE_BOTH);

			if($found_data) break;
		}

		if($found_data){
			return $found_data["instance"];
		}

	}
	//--------------------------------------------------------------------------------
}
