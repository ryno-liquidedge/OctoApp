<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class link extends \com\ui\intf\element {
	//--------------------------------------------------------------------------------
	// fields
	//--------------------------------------------------------------------------------
	protected static $is_singleton = true;
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		// init
		$this->name = "Link";
	}
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function build($options = []) {
		// public static function link($href, $label = false, $options = []) {

		// options
		$options = array_merge([
			"href" => false,
			"label" => false,

			"html" => true,
			"icon" => false,
			"icon_right" => false,
			"/icon" => [],
			"caret" => false,
			"badge" => false,
			"badge_color" => "primary",
			"/badge" => [],
			".ui-link" => true,
		], $options);

		// init
		$href = $options["href"];
		$label = $options["label"];
		if(!$label && !$options["/icon"]) {
			$options["/icon"][".me-2"] = false;
		}

		if($options["icon_right"] && is_string($options["icon_right"])) $options["icon"] = $options["icon_right"];

		$event_class = "";
  		if($href instanceof \com\ui\intf\element) $event_class = $href->get_class();

		// onclick / dropdown
		if ($event_class == "dropdown") {
			$options["@data-bs-toggle"] = "dropdown";
			$options["@href"] = "#";
			$options["@aria-haspopup"] = "true";
			$options["@aria-expanded"] = "false";
			if (!isset($options["@data-boundary"])) $options["@data-boundary"] = "viewport";
		}
		else if ($event_class == "offcanvas") {

			$options["@data-bs-toggle"] = "offcanvas";
			$options["@href"] = "#{$href->get_id()}";
			$options["@role"] = "button";
			$options["@aria-controls"] = $href->get_id();

		}
		else {
			if (preg_match("/^javascript:/i", $href)) $options["!click"] = preg_replace("/^javascript:/i", "", $href);
			else $options["@href"] = $href;
		}


		// html
		$html = \com\ui::make()->buffer();
		$html->a_($options);
			if ($options["icon"] && !$options["icon_right"]) $html->xicon($options["icon"], $options["/icon"]);
			$html->content($label, ["html" => $options["html"]]);
			if ($options["badge"]) $html->xbadge($options["badge"], $options["badge_color"], $options["/badge"]);
//			if ($options["badge"]) $html->span(".badge ^{$options["badge"]}");
			if ($options["caret"]) $html->content(" ")->b(".caret");
			if ($options["icon"] && $options["icon_right"]){
			    if(!$options["/icon"]){
                    $options["/icon"][".ms-2"] = true;
                    $options["/icon"][".me-2"] = false;
                }
			    $html->xicon($options["icon"], $options["/icon"]);
            }
		$html->_a();

		// dropdown
		if ($event_class == "dropdown") {
			$href->add_html(function($html) {
				$html->button(".d-none");
			});
			$href->set_trigger($html->get_clean());
			return $href->get(["#display" => "inline-block"]);
		}else if ($event_class == "offcanvas") {

			$href->set_trigger($html->get_clean());
			return $href->build();
		}

		// done
		return $html->get_clean();
	}
	//--------------------------------------------------------------------------------
}