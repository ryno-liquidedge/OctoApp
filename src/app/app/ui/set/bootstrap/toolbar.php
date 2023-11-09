<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * @package app\ui\set\bootstrap
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class toolbar extends \com\ui\set\bootstrap\toolbar {
	//--------------------------------------------------------------------------------
	// properties
	//--------------------------------------------------------------------------------
	protected $options = null;
	protected $item_arr = [];
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	public function __construct($options = []) {
		$options = array_merge([
		    "button_max" => \core::$app->get_instance()->get_option("com.ui.toolbar.button_max")
		], $options);

		$this->name = "Toolbar";
		$this->options = $options;
		
		$this->button_max = $options["button_max"];
		if ($this->button_max === null) {
			$this->button_max = 8;
		}
	}
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function add($html, $options = []) {
		// options
		$options = array_merge([
			".mb-2" => false,
		], $options);

		// support for html buffer
		if ($html instanceof \com\ui\intf\buffer) $html = $html->get_clean();
		if (is_callable($html)) $html = $html($this);

		// done
		$this->item_arr[] = [
			"type" => "html",
			"html" => $html,
			"options" => $options,
		];
	}
	//--------------------------------------------------------------------------------
	public function add_button($label, $onclick = false, $options = []) {
		// options
		$options = array_merge([
			".mb-2" => false,
		], $options);

		$this->item_arr[] = [
			"type" => "button",
			"label" => $label,
			"onclick" => $onclick,
			"options" => $options,
		];
	}
	//--------------------------------------------------------------------------------
	public function add_text($text, $options = []) {
		// options
		$options = array_merge([
			".mb-2" => false,
		], $options);

		$this->item_arr[] = [
			"type" => "text",
			"html" => $text,
			"options" => $options,
		];
	}
	//--------------------------------------------------------------------------------
	protected function build_items($html, $item_arr) {
		// init
		$small_margin = ".me-1";
		$large_margin = ".me-2";
		$this->prepare_items($item_arr,$this->button_max);
		$dropdown = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->dropdown();

		// items
		$button_group = false;
		foreach ($item_arr as $item_item) {
			switch ($item_item["type"]) {
				case "group-start" :
					$html->div_(".btn-group {$large_margin}", ["@role" => "group"]);
					$button_group = true;
					break;

				case "group-end" :
					$html->_div();
					$button_group = false;
					break;

				case "html" :
					$html->div(".input-group {$large_margin} *{$item_item["html"]}", array_merge(["@role" => "group"], $item_item["options"]));
					break;

				case "button" :
					// btn-group
					if (!$button_group) {
						if ($item_item["merge"]) {
							$dropdown_link_options = $item_item["options"];
							if (!empty($dropdown_link_options["msgdisabled"])) $dropdown_link_options["disabled"] = $dropdown_link_options["msgdisabled"];
							$dropdown->add_button($item_item["label"], $item_item["onclick"], $dropdown_link_options);
						}
						else {
							$html->div_(".btn-group {$small_margin}", ["@role" => "group"]);
							{
								$html->xbutton($item_item["label"], $item_item["onclick"], $item_item["options"]);
							}
							$html->_div();
						}
					}
					else {
						$html->xbutton($item_item["label"], $item_item["onclick"], $item_item["options"]);
					}
					break;

				case "text" :
					$html->div_(".input-group {$large_margin}", array_merge(["@role" => "group"], $item_item["options"]));
					$html->span(".form-control-plaintext *{$item_item["html"]}");
					$html->_div();
					break;

				case "divider" :
					$html->div(".ui-toolbar-divider");
					break;

				case "toolbar" :
					$this->build_items($html, $item_item["html"]->get_items());
					break;
			}
		}

		// more actions dropdown
		if (!$dropdown->is_empty()) {
			$html->div_(".btn-group {$small_margin}", ["@role" => "group"]);
			{
				$html->xbutton("More actions", $dropdown, ["icon" => "fas-bars"]);
			}
			$html->_div();
		}
	}
	//--------------------------------------------------------------------------------
}