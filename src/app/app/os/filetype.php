<?php

namespace LiquidedgeApp\Octoapp\app\app\os;

/**
 * Helper functions.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class filetype extends \com\os\filetype {
	//--------------------------------------------------------------------------------
	// static
	//--------------------------------------------------------------------------------
	public static function get_available_classes() {
		// init
		$class_arr = [];

		// find all the classes in the folder
		$file_arr = glob(\core::$folders->get_app_app()."/os/filetype/app.os.filetype.*.php");
		$file_arr = $file_arr + glob(\core::$folders->get_com()."/os/filetype/com.os.filetype.*.php");
		foreach ($file_arr as $file_item) {
			$pathinfo = pathinfo($file_item);
			$class_arr[] = substr($pathinfo["filename"], 16);
		}

		// done
		return $class_arr;
	}
	//--------------------------------------------------------------------------------
		/**
	 * @return \com\os\int\filetype
	 */
	public static function make($name) {
		// done
		$file = \core::$folders->get_app_app()."/os/filetype/app.os.filetype.{$name}.php";
		if (file_exists($file)){
			$class = "\\app\\os\\filetype\\{$name}";
		} else {
			$class = "\\com\\os\\filetype\\{$name}";
		}
		return $class::make();
	}
	//--------------------------------------------------------------------------------
}
