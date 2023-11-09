<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class header extends \com\ui\intf\element {
	//--------------------------------------------------------------------------------
	// fields
	//--------------------------------------------------------------------------------
	protected static $is_singleton = true;
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		// init
		$this->name = "Header";
	}
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function build($options = []) {
		// public static function header($size, $label, $sub_label = false, $options = []) {

		// options
		$options = array_merge([
			"size" => false,
			"label" => false,
			"sub_label" => false,
			"enable_border" => false,

			"icon" => false,
			"icon_left" => true,
			"/icon" => [],
			"html" => true,

			"info" => false,
            "/info" => [
                "icon" => false
            ],
		], $options);

		// init
		$size = $options["size"];
		$label = $options["label"];
		$sub_label = $options["sub_label"];
		if($options["enable_border"]) $options[".border-bottom-1px border-bottom-dashed border-bottom-secondary my-3 pb-2"] = true;

		// size
		if (!$size) $size = (\core::$panel == "mod" ? 1 : 2);

		// html
		$html = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();

		// header
		$html->{"h{$size}_"}($options);

		// icon
		if ($options["icon"] && $options["icon_left"]) {
			$html->xicon($options["icon"], array_merge(["space" => true], $options["/icon"]));
		}

		// label
		$html->content($label, ["html" => $options["html"]]);

		// icon
		if ($options["info"]) {

		    $icon = $options["/info"]["icon"];
		    if(!$icon) $icon = "fa-question-circle";

		    $options["/info"]["@title"] = $options["info"];
		    $options["/info"][".ms-2"] = true;
		    $options["/info"]["#font-size"] = "80%";

			$html->xicon($icon, array_merge(["space" => true], $options["/info"]));
		}

		// sub label
		if ($sub_label) {
			$html->small_(".text-muted .ms-2");
			$html->content($sub_label, ["html" => $options["html"]]);
			$html->_small();
		}

		if ($options["icon"] && !$options["icon_left"]) {
			$html->xicon($options["icon"], array_merge(["space" => true], $options["/icon"]));
		}

		// close header
		$html->{"_h{$size}"}();


		// done
		return $html->get_clean();
	}
	//--------------------------------------------------------------------------------
}