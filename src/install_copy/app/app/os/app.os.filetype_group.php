<?php

namespace app\os;
/**
 * Helper functions.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class filetype_group extends \com\os\filetype_group {
	//--------------------------------------------------------------------------------
	// static
	//--------------------------------------------------------------------------------
	public static function get_available_classes() {
		// init
		$class_arr = [];

		// find all the classes in the folder
		$file_arr = glob(\core::$folders->get_app_app()."/os/filetype_group/app.os.filetype_group.*.php");
		$file_arr = $file_arr + glob(\core::$folders->get_com()."/os/filetype/com.os.filetype_group.*.php");
		foreach ($file_arr as $file_item) {
			$pathinfo = pathinfo($file_item);
			$class_arr[] = substr($pathinfo["filename"], 22);
		}

		// done
		return $class_arr;
	}
		/**
	 * @return \com\os\int\filetype_group
	 */
	public static function make($name) {
		// done
		$class = "\\app\\os\\filetype_group\\{$name}";
		return $class::make();
	}
	//--------------------------------------------------------------------------------
}
