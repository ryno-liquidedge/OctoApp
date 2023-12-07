<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * @package app\ui\set\bootstrap
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class tab extends \com\ui\intf\tab {
	//--------------------------------------------------------------------------------
	// properties
	//--------------------------------------------------------------------------------
  	protected $tab_arr = []; // array of indexed tabs
  	protected $html_arr = [];
  	protected $options = [];
  	protected $nav_options = [];
  	protected $no_onclick = false; // removes the on click - add custom on click
  	protected $update_url = true; // removes the on click - add custom on click
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	/**
	 * Creates the component.
	 *
	 * @param string $url <p>Specify a url that will be loaded when the panel is displayed - false creates an empty panel unless a $start_index is specified.</p>
	 * @param string $options[id] <p>Use this component id instead of auto generating an unique one.</p>
	 * @param string $options[class] <p>Use this as the class html attribute for the wrapper div.</p>
	 * @param string $options[no_onclick] <p>Removes the !click events on buttons - add custom !click.</p>
	 */
    public function __construct($options = []) {
		// options
		$options = array_merge([
			"id" => false,
			"class" => "tab-panel",
			"no_onclick" => false,
		], $options);

		// no onclick
		if($options["no_onclick"]) $this->no_onclick = $options["no_onclick"];

        // options
		$options = array_merge([
			"id" => false,
			"class" => false,
		], $options);

		// id
		$this->id = ($options["id"] ? $options["id"] : \com\session::$current->session_uid);

		// class
		$this->class = $options["class"];
    }
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function build($options = []) {

        $this->options = $options;

  		ob_start();
  		$this->display();
  		return ob_get_clean();
	}
	//--------------------------------------------------------------------------------
	public function add($html) {
		$this->html_arr[] = $html;
	}
	//--------------------------------------------------------------------------------
	/**
     * Writes the tab navigation buttons as wel as the content panel's open div tag.
     */
    public function start() {
    	// justify
		$class_justify = false;
		if ($this->justified) $class_justify = " .nav-justified";

    	// html
		$html = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();

		// html: tabs
        $this->options[".nav nav-tabs{$class_justify} mb-2"] = true;
        $this->options["@data-id"] = $this->id;
		$html->ul_($this->options);
		foreach ($this->tab_arr as $tab_item) {
			$id = "{$this->id}btn{$tab_item["index"]}";
			$tab_item["/nav-item"]["@id"] = $id;
			$tab_item["/nav-item"][".nav-item"] = true;
			$html->li_($tab_item["/nav-item"]);

				// html: tab link
				$link_options = array_merge([
					"@onclick" => "{$this->id}.select({$tab_item["index"]});",
					"@data-url" => $tab_item["url"],
					"html" => true,
					"@type" => "link",
					".nav-link" => true,
				], $tab_item, $tab_item["/link"]);

				// html: no clicking
				if ($this->no_onclick) {
					$link_options["@onclick"] = false;
				}

				// html: disabled
				if ($tab_item["disabled"]) {
					$link_options["@onclick"] = false;
					$link_options[".text-muted"] = true;
					$link_options[".disabled"] = true;
				}

				// html: link
				$html->xlink(false, $tab_item["label"], $link_options);
			$html->_li();
		}
		$html->_ul();

		// javascript
		$js_arr = [];

		// javascript: new
		$csrf = \core::$app->get_response()->get_csrf();
		$js_arr[] = "var {$this->id} = new com_tab('{$this->id}', '{$csrf}');";

		// javascript: tabs
		foreach ($this->tab_arr as $tab_item) {
			$js_arr[] = "{$this->id}.addUrl('".strtr($tab_item["url"], ["'" => "\\'"])."');";
		}

		// javascript: options
		if (\core::$panel) $js_arr[] = "{$this->id}.setParent('".\core::$panel."');";
		if ($this->hidden_update) $js_arr[] = "{$this->id}.hidden_update = true;";
		if ($this->start_index !== false) $js_arr[] = "{$this->id}.refresh({$this->start_index}, {autoscroll:false, no_overlay:true});";

		// javascript: done
  		\LiquidedgeApp\Octoapp\app\app\js\js::add_script(implode(" ", $js_arr));

  		// panel
		$html->div_("#panel_{$this->id}", ["@class" => $this->class]);

		// done
		echo $html->get();
    }
  	//--------------------------------------------------------------------------------
  	/**
     * Writes the panel ending div tag.
     */
  	public function end() {
  		// html
		$html = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();

  		// panel
  		$html->_div();

        // start tab
        \LiquidedgeApp\Octoapp\app\app\js\js::add_script("{$this->id}.select({$this->start_tab_index}, {autoscroll:false, no_overlay:true});");

        // done
		echo $html->get();
  	}
  	//--------------------------------------------------------------------------------
  	/**
     * This function calls start() and end() directly after each other.
     */
  	public function display() {
		$this->start();
		$this->end();
  	}
  	//--------------------------------------------------------------------------------
	/**
	 * Adds a tab to the control.
	 *
	 * @param string $label <p>The text to display on the tab button.</p>
	 * @param string $url <p>The url for the content that the tab should load each time the corresponding button is clicked.</p>
	 * @param boolean $is_start_tab <p>Set this tab as the starting tab..</p>
	 */
  	public function add_tab($label, $url, $is_start_tab = false, $options = []) {
		// options
		$options = array_merge([
			"label" => false,
			"index" => false,
			"/link" => [],
			"/nav-item" => [],
			"url" => false,
			"no_ucfirst" => false,
			"disabled" => false,
		], $options);

		// init tab
		$options["label"] = $label;
		$options["index"] = count($this->tab_arr);
		$options["url"] = $url;

		// start tab
		if ($this->start_tab_index === false || $is_start_tab) {
			$this->start_tab_index = $options["index"];
		}

		// propercase
		if (!$options["no_ucfirst"]) {
			$options["label"] = ucfirst($options["label"]);
		}

		// done
		$this->tab_arr[$label] = $options;
  	}
  	//--------------------------------------------------------------------------------
}