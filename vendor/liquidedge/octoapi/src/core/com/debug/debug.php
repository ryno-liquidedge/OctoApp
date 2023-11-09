<?php

namespace octoapi\core\com\debug;

/**
 * Contains functions for quick debugging like displaying the contents of a variable
 * or showing the function stack at a specific point in the code.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class debug {
	//--------------------------------------------------------------------------------
	// static
	//--------------------------------------------------------------------------------
	public static function view($var, $options = []) {
		// options
		$options = array_merge([
			"show_detail" => false,
			"no_formatting" => false,
		], $options);

		// view method
		if (is_object($var) && method_exists($var, "__view")) {
			$var->__view();
			return;
		}

		// show variable value
		if (!$options["no_formatting"]) echo "<pre>";
		if ($options["show_detail"]) var_dump($var);
		else print_r($var);
		if (!$options["no_formatting"])echo "</pre>";
	}
	//--------------------------------------------------------------------------------
	public static function console($var, $options = []) {
		// options
		$options = array_merge([
			"show_detail" => false,
			"no_formatting" => true,
			"append" => false,
		], $options);

		// filepath
		$filepath = \octoapi\OctoApi::$dir_temp;
		$filepath .= "/console";
		if ($options["append"]) $filepath .= "-{$options["append"]}";
		$filepath .= ".txt";

		// create dir
        $dirname = dirname($filepath);
        if(!is_dir($dirname)) mkdir($dirname);

		ob_start();
		self::view($var, $options);
		file_put_contents($filepath, ob_get_clean().PHP_EOL, FILE_APPEND);
	}
	//--------------------------------------------------------------------------------
}