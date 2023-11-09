<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * @package app\ui\set\bootstrap
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class menu extends \com\ui\intf\menu {
	//--------------------------------------------------------------------------------
	// properties
	//--------------------------------------------------------------------------------
  	protected $item_arr = []; // array of items
  	protected $prepend_html_arr = []; // array of html to prepend
  	protected $append_html_arr = []; // array of html to append
  	protected $html = null;
  	protected $listgroup = false;
	protected $options = null;
	protected $header = [];
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	/**
	 * Creates the component.
	 *
	 */
    public function __construct($options = []) {
		// options
		$options = array_merge([
			"id" => false,
			"disable_collapse" => false,
			"disable_headers" => false,
		], $options);

		$this->name = "Menu";
		$this->options = $options;

		// id
		$this->id = ($options["id"] ? $options["id"] : \com\session::$current->session_uid);
    }
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function build($options = []) {
    	return $this->get($options);
	}
	//--------------------------------------------------------------------------------
	public function get($options = []) {
		// options
		$options = array_merge([
			"@id" => $this->id,
			".d-none" => true,
			".mb-3" => true,
			".position-relative" => true,
		], $this->options, $options);

		// buffer
		$this->html = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();

		// headers enabled must disable collaps
		if (!$options["disable_headers"]) {
			$options["disable_collapse"] = true;
		}

		// icon: expand
		if (!$options["disable_collapse"]) {
			$this->html->xicon("fas-bars", [
				"@id" => "{$this->id}_expand",
				"!click" => "{$this->id}.toggle();",
				".fa-2x" => true,
				".d-none collapse-open ml-3" => true,
				"#cursor" => "pointer",
				"#position" => "absolute",
				"#z-index" => 2,
			]);
		}

		// wrapper
		$this->html->div_(".ui-menu", $options);

		// icon: collapse
		if (!$options["disable_collapse"]) {
			$this->html->xicon("fas-times-circle", [
				"@id" => "{$this->id}_collapse",
				"!click" => "{$this->id}.toggle();",
				".float-right" => true,
				".fa-lg" => true,
				".collapse-close" => true,
				"#cursor" => "pointer",
				"#position" => "absolute",
				"#right" => $this->header ? "10px" : "-15px",
				"#top" => $this->header ? "5px" : "-9px",
				"#z-index" => 2,
				"#background" => "#FFFFFF",
				"#border-radius" => "14px",
			]);
		}

        if($this->header) {
		    $this->html->div(array_merge(["*" => $this->header["label"]], $this->header));
        }

		foreach ($this->prepend_html_arr as $prepend){
		    $this->html->add($prepend);
        }


		{
			// items
			$link_nr = 0;
			foreach ($this->item_arr as $item_item) {
				if($item_item["type"] == "collapse"){
					$this->build_collapse($item_item, $link_nr);
				}else{
					$this->build_link($item_item, $link_nr);
				}
				$link_nr++;
			}
		}

		foreach ($this->append_html_arr as $append){
		    $this->html->add($append);
        }

		$this->html->_div();

    	// generate page id for storage
    	$page_id = \core::$app->get_request()->get_page_id();

    	// javascript
		$js_arr[] = "var {$this->id} = new com_menu('{$this->id}', '{$page_id}', {disable_collapse: '{$options["disable_collapse"]}', disable_headers: '{$options["disable_headers"]}'});";

		// javascript: done
		\com\js::add_script(implode(" ", $js_arr));

		// done
		return $this->html->get_clean();
	}
	//--------------------------------------------------------------------------------
	/**
	 * @return array
	 */
	public function get_item_arr(): array {
		return $this->item_arr;
	}
	//--------------------------------------------------------------------------------
    public function prepend($html) {

        if(is_callable($html)) $this->prepend_html_arr[] = $html();
        else $this->prepend_html_arr[] = $html;
    }
	//--------------------------------------------------------------------------------
    public function append($html) {

        if(is_callable($html)) $this->append_html_arr[] = $html();
        else $this->append_html_arr[] = $html;

    }
	//--------------------------------------------------------------------------------
	protected function build_link($item, $index) {
		// listgroup
		if (!$this->listgroup) {
			$this->listgroup = $this->html->div_(".list-group");
		}

		// don't allow empty headers without info
		if (!$item["!click"] && !$item["info_arr"]) {
			return;
		}

		// label
		$label = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->tag()->span(".menu-label ^{$item["label"]}");

		// id
		$id = "{$this->id}link{$index}";

		// onclick
		if ($item["!click"] && !$item["disabled"] && !$item["info_arr"]) {
			// ensure js statement is closed
			if (substr($item["!click"], -1) != ";") {
				$item["!click"] .= ";";
			}
			$item["!click"] = "{$item["!click"]} ";
		}
		elseif ($item["info_arr"]) {
			$item["!click"] = "{$this->id}.select(this);";
		}

		// options

		$options = [
			"!click" => $item["!click"],
			".list-group-item" => true,
			"@data-index" => $index,
			".com-html-link-alone" => false,
			"html" => true,
			"icon" => $item["icon"],
			"/icon" => $item["/icon"],
			"badge" => $item["badge"],
			"@disabled" => $item["disabled"],
			".disabled cursor-not-allowed" => $item["disabled"],
			"@id" => $id,
			"@tooltip" => ($item["@tooltip"] ?? false),
			"@tooltip" => $item["label"],
			"#z-index" => 1,
		];

		$class_arr = \LiquidedgeApp\Octoapp\app\app\ui\arr::extract_signature_items(".", $item);
		foreach ($class_arr as $class => $is_enabled) $options[".$class"] = $is_enabled;

		$class_arr = \LiquidedgeApp\Octoapp\app\app\ui\arr::extract_signature_items("@", $item);
		foreach ($class_arr as $attr => $is_enabled) $options["@$attr"] = $is_enabled;

		// disabled message
		if ($item["msgdisabled"]) {
			$options["@disabled"] = true;
			$options["!click"] = false;
			$options["@data-toggle"] = "tooltip";
			$options["@title"] = $item["msgdisabled"];
		}

		// collapse
		if ($item["info_arr"]) {
			$options["@data-toggle"] = "collapse";
			$options["@data-bs-target"] = "#info{$index}";
			$options["@aria-expanded"] = "false";
			$options["@aria-controls"] = "#info{$index}";
		}

		// click
		if (preg_match("/^javascript:/i", $options["!click"])) $options["!click"] = preg_replace("/^javascript:/i", "", $options["!click"]);

		// link
		$this->html->a_($options);
		{
			if ($options["icon"]) $this->html->xicon($options["icon"], $options["/icon"]);

			if($options["html"]) $label = html_entity_decode($label);

			$this->html->add($label);

			if (\LiquidedgeApp\Octoapp\app\app\data\data::is_html($options["badge"])) $this->html->add(html_entity_decode($options["badge"]));
			else if ($options["badge"]) $this->html->span(".badge ^{$options["badge"]}");
		}
		$this->html->_a();

		// info items
		if ($item["info_arr"]) {
			$this->html->div_("#info{$index} .card .card-body .collapse .multi-collapse", ["#width" => "90%", "#position" => "relative", "#left" => "27px", "#margin-bottom" => "-1px"]);
				foreach ($item["info_arr"] as $info_item) {
					$this->html->div("*{$info_item["value"]}");
				}
			$this->html->_div();
		}
	}
	//--------------------------------------------------------------------------------
	/**
	 * @param $item
	 * @param $index
	 * @return string
	 */
	public function get_link($item, $index = false) {

		$item = array_merge([
		    "!click" => false,
		    "@href" => false,
		    "info_arr" => [],
			"disabled" => false,
			"badge" => false,
			"icon" => false,
			"/icon" => [],
			"type" => "link",
			"label" => false,
			"tooltip" => false,
			"msgdisabled" => false,
		], $item);

    	$buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();
		// listgroup
		if (!$this->listgroup) {
			$this->listgroup =$buffer->div_(".list-group");
		}

		// don't allow empty headers without info
		if (!$item["!click"] && !$item["info_arr"] && !isset($item["@href"])) {
			return "";
		}
		// label
		$label = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->tag()->span(".menu-label ^{$item["label"]}");

		// id
		$id = "{$this->id}link{$index}";

		// onclick
		if ($item["!click"] && !$item["disabled"] && !$item["info_arr"]) {
			// ensure js statement is closed
			if (substr($item["!click"], -1) != ";") {
				$item["!click"] .= ";";
			}
			$item["!click"] = "{$item["!click"]} {$this->id}.select(this);";
		}
		elseif ($item["info_arr"]) {
			$item["!click"] = "{$this->id}.select(this);";
		}

		// options

		$options = [
			"@href" => $item["@href"],
			"!click" => $item["!click"],
			".list-group-item" => true,
			"@data-index" => $index,
			".ui-link" => true,
			".com-html-link-alone" => false,
			"html" => true,
			"icon" => $item["icon"],
			"/icon" => $item["/icon"],
			"icon_right" => isset($item["icon_right"]) ? $item["icon_right"] : false,
			"/icon_right" => isset($item["/icon_right"]) ? $item["/icon_right"] : [],
			"badge" => $item["badge"],
			"@disabled" => $item["disabled"],
			"@id" => $id,
			"@tooltip" => ($item["@tooltip"] ?? false),
			"@tooltip" => $item["label"],
			"#z-index" => 1,
		];

		$class_arr = \LiquidedgeApp\Octoapp\app\app\ui\arr::extract_signature_items(".", $item);
		foreach ($class_arr as $class => $is_enabled) $options[".$class"] = $is_enabled;

		$class_arr = \LiquidedgeApp\Octoapp\app\app\ui\arr::extract_signature_items("@", $item);
		foreach ($class_arr as $attr => $is_enabled) $options["@$attr"] = $is_enabled;

		// disabled message
		if ($item["msgdisabled"]) {
			$options["@disabled"] = true;
			$options["!click"] = false;
			$options["@data-bs-toggle"] = "tooltip";
			$options["@title"] = $item["msgdisabled"];
		}

		// collapse
		if ($item["info_arr"]) {
			$options["@data-bs-toggle"] = "collapse";
			$options["@data-bs-target"] = "#info{$index}";
			$options["@aria-expanded"] = "false";
			$options["@aria-controls"] = "#info{$index}";
		}

		// click
		if (preg_match("/^javascript:/i", $options["!click"])) $options["!click"] = preg_replace("/^javascript:/i", "", $options["!click"]);

		// link
		$buffer->a_($options);
		{
			if ($options["icon"]) $buffer->xicon($options["icon"], $options["/icon"]);

			if($options["html"]) $label = html_entity_decode($label);

			$buffer->add($label);

			if (\LiquidedgeApp\Octoapp\app\app\data\data::is_html($options["badge"])) $buffer->add(html_entity_decode($options["badge"]));
			else if ($options["badge"]) $buffer->span(".badge ^{$options["badge"]}");

			if ($options["icon_right"]) $buffer->xicon($options["icon_right"], $options["/icon_right"]);
		}
		$buffer->_a();

		// info items
		if ($item["info_arr"]) {
			$buffer->div_("#info{$index} .card .card-body .collapse .multi-collapse", ["#width" => "90%", "#position" => "relative", "#left" => "27px", "#margin-bottom" => "-1px"]);
				foreach ($item["info_arr"] as $info_item) {
					$buffer->div("*{$info_item["value"]}");
				}
			$buffer->_div();
		}

		return $buffer->build();

	}
	//--------------------------------------------------------------------------------
	protected function build_collapse($item, $index) {


		$options = [
    		"label" => $item["label"],

    		"icon" => $item["icon"],
			"/icon" => $item["/icon"],

    		"icon_right" => "fa-chevron-right",
			"/icon_right" => [".float-right ui-collapse-icon mt-1" => true],

    		"@href" => "#{$item["id"]}",
    		"@data-bs-toggle" => "collapse",
    		"@role" => "button",
    		"@aria-expanded" => "false",
    		"@aria-controls" => $item["id"],
    		".list-group-item" => true,
			"@data-index" => $index,
    		".collapsed" => true,
			".com-html-link-alone" => false,
		];

		if($item[".show"]){
    		$options["icon_right"] = "fa-chevron-down";
    		$options[".collapsed"] = false;
		}

    	$buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();
    	$buffer->add($this->get_link($options, $index));

		$buffer->div_([".collapse" => true, ".show" => $item[".show"], "@id" => $item["id"]]);
			foreach ($item["link_arr"] as $link){
				$buffer->div_([".ml-2" => true]);
					$buffer->add($link["html"]);
				$buffer->_div();
			}
		$buffer->_div();

		$this->html->add($buffer->build());
	}
	//--------------------------------------------------------------------------------
  	public function add_html($html, $options = []) {
		// options
		$options = array_merge([
			"type" => "html",
			"html" => $html,
		], $options);

		// done
		$this->item_arr[\com\str::get_random_alpha(5)] = $options;
  	}

  	//--------------------------------------------------------------------------------
    public function add_collapse($label, $link_arr, $icon = false, $options = []){

    	// options
		$options = array_merge([
			"disabled" => false,
			".show" => false,
			"badge" => false,
			"icon" => $icon,
			"/icon" => [".mr-2" => true],
			"type" => "collapse",
			"label" => $label,
			"link_arr" => $link_arr,
			"tooltip" => false,
			"info_arr" => [],
		], $options);

		// done
		$this->item_arr[$label] = $options;

    }

  	//--------------------------------------------------------------------------------
  	public function add_link($label, $onclick, $icon = false, $options = []) {
		// options
		$options = array_merge([
			"disabled" => false,
			"badge" => false,
			"/badge" => [],
			"icon" => $icon,
			"/icon" => [],
			"!click" => $onclick,
			"type" => "link",
			"label" => $label,
			"tooltip" => false,
			"msgdisabled" => false,
			"info_arr" => [],
		], $options);

		// init tab
		$options["label"] = $label;
		$options["!click"] = $onclick;

		// done
		$this->item_arr[$label] = $options;
  	}
  	//--------------------------------------------------------------------------------
	public function add_info($item_index, $value) {
    	// check if entry exists
		if (!$item_index || !isset($this->item_arr[$item_index])) {
			$item_index = "General details";
			$this->set_last_header($item_index);
			if (!isset($this->item_arr[$item_index])) {
				$this->add_link($item_index, false, "caret-right", ["/icon" => [".float-right" => true]]);
			}
		}

		$this->item_arr[$item_index]["info_arr"][] = [
			"value" => $value,
		];
	}
  	//--------------------------------------------------------------------------------
	public function add_value($item_index, $label, $value, $type = false, $options = []) {
		// options
		$options = array_merge([
			"vertical" => true,
		], $options);

		// value
  		$value = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->form_value3($label, $value, $type, $options);

  		// add info
  		$this->add_info($item_index, $value);
	}
  	//--------------------------------------------------------------------------------
	public function add_dbvalue($item_index, $obj, $field, $options = []) {
		// options
		$options = array_merge([
			"vertical" => true,
		], $options);

		// value
		$value = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->dbvalue($obj, $field, $options);

		// add info
		$this->add_info($item_index, $value);
	}
	//--------------------------------------------------------------------------------
    public function header($label, $options = []) {
        $this->header = array_merge([
            "label" => $label,
            "@class" => "font-25 h4"
        ], $options);
    }
  	//--------------------------------------------------------------------------------
}