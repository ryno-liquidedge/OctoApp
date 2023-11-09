<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * @package app\ui\set\bootstrap
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class tab_static extends \com\ui\intf\element {
	//--------------------------------------------------------------------------------
	// properties
	//--------------------------------------------------------------------------------
  	protected $tab_arr = [];
  	protected $active_tab = false;
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
    public function __construct($options = []) {
    }
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function add_tab($title, $html, $options = []) {

    	$options = array_merge([
    	    "id" => \LiquidedgeApp\Octoapp\app\app\str\str::get_random_id("tab"),
    	    "active" => false,
    	], $options);

    	$options["title"] = $title;

    	if(is_callable($html)) $html = $html();
    	$options["html"] = $html;

    	if($options["active"])
    		$this->active_tab = $options;

    	$this->tab_arr[] = $options;

	}
	//--------------------------------------------------------------------------------
	public function build($options = []) {

    	$options = array_merge([
    		"@role" => "tablist",
			".nav" => true,
			".nav-tabs" => true,
			".nav-pills" => false,
			"/tab" => [],
			"/tab-panel" => [],
		], $options);

    	$buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();

        $first_index = \LiquidedgeApp\Octoapp\app\app\ui\arr::get_first_index($this->tab_arr);
        if(!$this->active_tab)
            $this->tab_arr[$first_index]["active"] = true;

        $buffer->ul_($options);
            foreach ($this->tab_arr as $key => $tab){
                $tab = array_merge($options["/tab"], $tab);
                $this->fn_tab($buffer, $tab);
            }
        $buffer->_ul();

        $buffer->div_([".tab-content" => true, ]);
            foreach ($this->tab_arr as $key => $tab){
                $tab_panel = array_merge($options["/tab-panel"], $tab);
                $this->fn_tab_panel($buffer, $tab_panel);
            }
        $buffer->_div();


		return $buffer->build();

	}
  	//--------------------------------------------------------------------------------
	private function fn_tab(&$buffer, $options = []){
    	$tab = array_merge([
			"@role" => "presentation",
			".nav-item" => true,
			"icon" => false,
			"/link" => [],
		], $options);

		$buffer->li_($tab);

			$link = array_merge([
				"@role" => "tab",
				"@data-bs-toggle" => "tab",
				"@data-bs-target" => "#{$tab["id"]}",
				".nav-link" => true,
				".active" => $tab["active"],
				"@aria-selected" => $tab["active"],
				"@type" => "button",
				"@aria-controls" => $tab["id"],
			], $tab["/link"]);

			$buffer->button_($link);
				if($tab["icon"]) $buffer->xicon($tab["icon"]);
				$buffer->add($tab["title"]);
			$buffer->_button($link);

		$buffer->_li();
	}
  	//--------------------------------------------------------------------------------
	private function fn_tab_panel(&$buffer, $options = []){
    	$tab = array_merge([
			"@id" => $options["id"],
			"@role" => "tabpanel",
			".tab-pane" => true,
			".active" => $options["active"]
		], $options);

		$buffer->div_($tab);
			$buffer->add($tab["html"]);
		$buffer->_div();
	}
  	//--------------------------------------------------------------------------------
}