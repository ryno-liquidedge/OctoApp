<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * @package app\ui\set\bootstrap
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class badge extends \com\ui\intf\element {
	//--------------------------------------------------------------------------------
	// fields
	//--------------------------------------------------------------------------------
	protected static $is_singleton = true;
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		// init
		$this->name = "Badge";
	}
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function build($options = []) {
		// public static function tag($content, $color = "blue", $options = []) {

		// options
		$options = array_merge([
			"content" => false,
			"color" => false,
			"icon" => false,
			"/icon" => [],
			"icon_onclick" => false,
			"icon_right" => false,
			"@href" => false,
  		], $options);

		// colours
		$type_map_arr = [
			"primary" => "primary",
			"warning" => "warning",
			"danger" => "danger",
			"info" => "info",
			"orange" => "orange",
			"green" => "green",
			"blue" => "blue",
			"red" => "red",
			"gray" => "gray",
		];

		// init
		$content = $options["content"];
		if(is_callable($content)) $content = $content();
		$color = $options["color"];

		if($options["icon"]) {
			if(\LiquidedgeApp\Octoapp\app\app\data\data::is_html($options["icon"])){
				if($options["icon_right"]){
					$content = "$content {$options["icon"]}";
				}else{
					$content = "{$options["icon"]} $content";
				}
			}else{

				if(!$options["/icon"]){
					if($options["icon_right"]){
						$options["/icon"][".ms-2"] = true;
						$options["/icon"][".me-2"] = false;
					}else{
						$options["/icon"][".ms-2"] = false;
						$options["/icon"][".me-2"] = true;
					}
				}
				$icon = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->icon($options["icon"], $options["/icon"]);
				if($options["icon_onclick"]) $icon = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->iconbutton(false, $options["icon_onclick"], $options["icon"], $options["/icon"]);

				if(!$options["icon_right"]){
					$content = $icon." $content";
				}else{
					$content = "$content ".$icon;
				}

			}
		}

		// map
		$type = (isset($type_map_arr[$color]) ? $type_map_arr[$color] : $color);

		if(substr($color, 0, 1) == "#"){
			$type = "grey";
			$options["#background"] = "$color !important";
			$options["#background-color"] = "$color !important";
		}

		$span = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->tag()->span(".badge .badge-{$type} *{$content}", $options);

		if($options["@href"]){
			$buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();
			$buffer->a_(["@href" => $options["@href"]]);
				$buffer->add($span);
			$buffer->_a();

			return $buffer->build();
		}

		// done
		return $span;
	}
	//--------------------------------------------------------------------------------
}