<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class iconbutton extends \com\ui\intf\element {
	//--------------------------------------------------------------------------------
	// fields
	//--------------------------------------------------------------------------------
	protected static $is_singleton = true;
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		// init
		$this->name = "Icon button";
	}
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function build($options = []) {
		// public static function iconbutton($label = false, $onclick = false, $icon = false, $options = []) {

		$options = array_merge([
			"label" => false,
			"onclick" => false,
			"icon" => false,
  		], $options);

		// init
		$label = $options["label"];
		$onclick = $options["onclick"];
		$icon = $options["icon"];

		// return empty space when no icon is given
		if (!$icon) {
			// class
			return \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->icon($icon, $options);
		}

		// class
		$options[".cursor-pointer"] = true;

		// onclick
		if ($onclick) {
			$options["!click"] = $onclick;
		}

		// tooltip
 		if ($label) {
 			if (is_array($label)) $label = implode("<br />", $label);
			$options["@title"] = $label;
 			\LiquidedgeApp\Octoapp\app\app\ui\ui::make()->tooltip();
 		}

		// done
		return \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->icon($icon, $options);
	}
	//--------------------------------------------------------------------------------
}