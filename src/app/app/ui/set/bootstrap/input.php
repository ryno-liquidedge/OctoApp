<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class input extends \com\ui\intf\element {
	//--------------------------------------------------------------------------------
	// fields
	//--------------------------------------------------------------------------------
	protected static $is_singleton = true;
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		// init
		$this->name = "Input";
	}
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function build($options = []) {
		//public static function input($type, $id, $value = false, $options = []) {

		// shared
		// options
  		$options = array_merge([
			"/" => true,
			"type" => false,
			"id" => false,
			"value" => false,

			"defaultvalue" => true,
  		], $options);

  		// init
		$options["@type"] = $options["type"];
		$options["@id"] = $options["id"];
		$options["@name"] = isset($options["@name"]) ? $options["@name"] : $options["id"];

		// value
		$options["@value"] = htmlentities($options["value"]);

		// default value
		if ($options["defaultvalue"]) $options["@defaultvalue"] = $options["@value"];

		// done
		return \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->tag()->input(false, $options);
	}
	//--------------------------------------------------------------------------------
}