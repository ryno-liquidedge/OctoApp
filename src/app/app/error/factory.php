<?php

namespace LiquidedgeApp\Octoapp\app\app\error;

/**
 * Factory for creating error related classes.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class factory {
	//--------------------------------------------------------------------------------
	/**
	 * @param $component
	 * @param $name
	 * @param array $options
	 * @return \com\error\exception
	 */
	public static function make_component_exception($component, $name, $options = []) {
		// options
		$options = array_merge([
			"message" => null,
			"code" => 0,
			"previous_exception" => null,
		], $options);

		// build and include exception class file
		$filepath = \core::$folders->get_com()."/{$component}/exception/com.{$component}.exception.{$name}.php";
		if (!file_exists($filepath)) $filepath = \core::$folders->get_app_app()."/{$component}/exception/com.{$component}.exception.{$name}.php";
		if (!file_exists($filepath)) throw self::make_exception("Cannot find exception class file", 1, $options);
		include_once($filepath);

		// instance exception
		$class = "com\\{$component}\\exception\\{$name}";
		$instance = new $class($options["message"], $options["code"], $options["previous_exception"]);

		// done
		return $instance;
	}
	//--------------------------------------------------------------------------------

	/**
	 * @param $message
	 * @param $code
	 * @param array $options
	 * @return \com\error\exception
	 */
	public static function make_exception($message, $code, $options = []) {
		// options
		$options = array_merge([
			"previous_exception" => null,
		], $options);

		// message and code
		$options["message"] = $message;
		$options["code"] = $code;

		// done
		return self::make_component_exception("error", "generic", $options);
	}
    //--------------------------------------------------------------------------------
}