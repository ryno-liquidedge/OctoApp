<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class dropdown extends \com\ui\intf\dropdown {
	//--------------------------------------------------------------------------------
	// fields
	//--------------------------------------------------------------------------------
	protected $item_arr = [];
	protected $html = false;
	protected $trigger = false;
	protected $align = false;
	protected $options = null;
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	public function __construct($options = []) {

		$options = array_merge([
		    "animate" => false,
		    "/ul" => [],
		], $options);

		if($options["animate"]){
			$options["/ul"][".animate slide-in"] = true;
		}

		// init
		$this->name = "Dropdown";
		$this->set_align("end");
		$this->options = $options;
	}
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function build($options = []) {
		return $this->get($options);
	}
	//--------------------------------------------------------------------------------
	public function set_option($key, $value) {
		$this->options[$key] = $value;
	}
	//--------------------------------------------------------------------------------
	/**
	 * Builds and returns the HTML for the dropdown.
	 *
	 * @param array $options <p>Any @attriute, #style or !event options available on an html tag used on the dropdown wrapper.</p>
	 */
	public function get($options = []) {
		// options
		$options = array_merge([
			"/ul" => []
		], $this->options, $options);

		// buffer
		$this->html = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();

		// html
		$this->html->div_(".dropdown", $options);

			// init
			$ul_options = array_merge([
				"@role" => "menu",
			], $options["/ul"]);

			// trigger
			$this->html->add($this->trigger);

			// align
			if ($this->align) $ul_options[".dropdown-menu-{$this->align}"] = true;

			// dropdown
			$this->html->div_(".dropdown-menu", $ul_options); // aria-labelledby="dropdownMenu1"
			$this->build_items();
			$this->html->_div();

		$this->html->_div();

		// done
		return $this->html->get();
	}
	//--------------------------------------------------------------------------------
	public function set_trigger($html) {
		$this->trigger = $html;
	}
	//--------------------------------------------------------------------------------
	public function set_align($align) {
		if ($align != "right") $align = false;
		$this->align = $align;
	}
	//--------------------------------------------------------------------------------
	/**
	 * @param $label
	 * @param $href
	 * @param array $options
	 */
	public function add_link($label, $href, $options = []) {
		// options
		$options = array_merge([
			"disabled" => false,
			"icon" => false,

			"type" => "link",
			"label" => $label,
			"href" => $href,
		], $options);

		// add item
		$this->item_arr[] = $options;
	}
	//--------------------------------------------------------------------------------

	/**
	 * @param $label
	 * @param $onclick
	 * @param array $options
	 */
	public function add_button($label, $onclick, $options = []) {
		// options
		$options = array_merge([
			"disabled" => false,
			"icon" => false,

			"type" => "button",
			"label" => $label,
			"onclick" => $onclick,
		], $options);

		// add item
		$this->item_arr[] = $options;
	}
	//--------------------------------------------------------------------------------
	public function add_links_fromarray($arr, $onclick, $options = []) {
		// options
		$options = array_merge([
		], $options);

		// add each link
		foreach ($arr as $arr_index => $arr_item) {
			if (!$arr_index) continue;
			$this->add_link($arr_item, strtr($onclick, ["%index%" => $arr_index]), $options);
		}
	}
	//--------------------------------------------------------------------------------
	/**
	 * Adds an header item to the dropdown.
	 *
	 * @param string $header <p>The header text.</p>
	 */
	public function add_header($header) {
		// add item
		$this->item_arr[] = [
			"type" => "header",
			"header" => $header,
		];
	}
	//--------------------------------------------------------------------------------
	/**
	 * Adds a divider item to the dropdown.
	 */
	public function add_divider() {
		// add item
		$this->item_arr[] = [
			"type" => "divider",
		];
	}
	//--------------------------------------------------------------------------------
	/**
	 * Adds html to the dropdown.
	 */
	public function add_html($fn_html) {
		// add item
		array_unshift($this->item_arr, [
			"type" => "html",
			"fn_html" => $fn_html,
		]);
	}
	//--------------------------------------------------------------------------------
	/**
	 * Checks if the dropdown contains any links.
	 */
	public function is_empty() {
		return !$this->item_arr;
	}
	//--------------------------------------------------------------------------------
	/**
	 * Checks if the dropdown contains any links.
	 */
	public function has_links() {
		return (false !== \LiquidedgeApp\Octoapp\app\app\arr\arr::find("link", $this->item_arr, "type"));
	}
	//--------------------------------------------------------------------------------
	// internal
	//--------------------------------------------------------------------------------
	protected function build_items() {
		foreach ($this->item_arr as $item_item) {
			switch ($item_item["type"]) {
				case "html" : $this->build_html($item_item); break;
				case "link" : $this->build_link($item_item); break;
				case "button" : $this->build_button($item_item); break;
				case "header" : $this->build_header($item_item); break;
				case "divider" : $this->build_divider(); break;
			}
		}
	}
	//--------------------------------------------------------------------------------
	protected function build_link($item) {
		// options
		$options = array_merge([
			".dropdown-item" => true,
			"icon" => $item["icon"],
		], $item);

		// disabled
		if ($item["disabled"]) {
			$options["@disabled"] = true;

			// tootltip
			switch (true) {
				case is_array($item["disabled"]) :
				case is_string($item["disabled"]) :
					$options["msgdisabled"] = $item["disabled"];
					break;
			}
			$item["href"] = "#";
		}

		// html
		$this->html->xlink($item["href"], $item["label"], $options);
	}
	//--------------------------------------------------------------------------------
	protected function build_button($item) {
		// options
		$options = array_merge([
			"@type" => "button",
			".dropdown-item" => true,
			"icon" => $item["icon"],
		], $item);

		// disabled
		if ($item["disabled"]) {
			$options["@disabled"] = true;

			// tootltip
			switch (true) {
				case is_array($item["disabled"]) :
				case is_string($item["disabled"]) :
					$options["msgdisabled"] = $item["disabled"];
					break;
			}
			$item["onclick"] = "void(0);";
		}

		// html
		$this->html->xbutton($item["label"], $item["onclick"], $options);
	}
	//--------------------------------------------------------------------------------
	protected function build_header($item) {
		// html
		$this->html->h6(".dropdown-header ^{$item["header"]}");
	}
	//--------------------------------------------------------------------------------
	protected function build_divider() {
		// html
		$this->html->div(".dropdown-divider");
	}
	//--------------------------------------------------------------------------------
	protected function build_html($item) {
		$item["fn_html"]($this->html);
	}
	//--------------------------------------------------------------------------------
}