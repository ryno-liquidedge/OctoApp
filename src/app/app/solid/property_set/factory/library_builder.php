<?php

namespace LiquidedgeApp\Octoapp\app\app\solid\property_set\factory;

/**
 * @package app\property_set\factory
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class library_builder extends \LiquidedgeApp\Octoapp\app\app\intf\runnable {

	//--------------------------------------------------------------------------------
	public function run($options = []) {
		$this->build();
	}
	//--------------------------------------------------------------------------------
	private function get_solid_arr_string() {

		$return_arr = [];

		$fn_load_folder = function($folder)use(&$return_arr){
			foreach (glob("$folder/*") as $filename) {
			    if(!is_file($filename)) continue;
				$class_name = $this->get_class_name_from_filename($filename);
				$instance = call_user_func([$class_name, "make"]);

				$class_name = str_replace("\\", "\\\\", $this->get_class_name_from_filename($filename));
				$filename = basename($filename);

				$return_arr[] = <<<EOD
"{$instance->get_code()}" => [
	"filename" => "{$filename}",
	"classname" => "{$class_name}",
	"key" => "{$instance->get_key()}",
	"code" => "{$instance->get_code()}",
],
EOD;

			}
		};

		//first load local set
		$glob_arr = glob(__DIR__."../solid_classes/*");
		foreach ($glob_arr as $folder) $fn_load_folder($folder);

		//load app versions
		$glob_arr = glob(\core::$folders->get_app_app() . "/solid/property_set/solid_classes/*");
		foreach ($glob_arr as $folder) $fn_load_folder($folder);

		return $return_arr;

	}
	//--------------------------------------------------------------------------------
	public function get_class_name_from_filename($filename) {
		$basename = basename($filename);
		return "\\" . str_replace(".", "\\", str_replace(".php", "", $basename));
	}
	//--------------------------------------------------------------------------------
	private function build() {

		$solid_arr_string = $this->get_solid_arr_string();

		if ($solid_arr_string) {
			$dir = \core::$folders->get_app_app() . "/solid/property_set/incl";
			$filename = "app.solid.property_set.incl.library.php";

			\LiquidedgeApp\Octoapp\app\app\os\os::mkdir($dir);

			$str = implode("\n", $solid_arr_string);

			$content = <<<EOD
<?php

namespace app\solid\property_set\incl;

/**
 * @package app\property_set\incl
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class library {
	//--------------------------------------------------------------------------------
	public static \$solid_arr = [
		$str
	];
	//--------------------------------------------------------------------------------
}

EOD;

			file_put_contents("$dir/$filename", $content);
		}

	}
	//--------------------------------------------------------------------------------
}
