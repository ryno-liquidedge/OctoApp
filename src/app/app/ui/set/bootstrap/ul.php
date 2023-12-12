<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class ul extends \com\ui\intf\element {
	//--------------------------------------------------------------------------------
	// properties
	//--------------------------------------------------------------------------------
	protected $item_arr = [];

	protected $options = [];

	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	public function __construct($options = []) {

		// init
        $this->name = "HTML List";
		$this->options = $options;

	}
	//--------------------------------------------------------------------------------
	public function add_item($html, $options = []) {

		$options = array_merge([
		    "icon" => false,
		], $options);

		if(is_callable($html)) $html = $html();

		$options["html"] = $html;

		$this->item_arr[] = $options;

	}
	//--------------------------------------------------------------------------------
	public function add_li($mixed, $title = false, $options = []) {

		$options = array_merge([
		    "title" => $title,
		    "icon" => false,
		    "inline" => true,
		    "/title" => [".me-2" => true],
		    "/content" => [],
		    "/wrapper" => [".d-flex mb-2" => true],
		], $options);


		$this->add_item(function()use($mixed, $options){
			if(is_callable($mixed)) $mixed = $mixed();

			$buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();
			$buffer->div_($options["/wrapper"]);
				if($options["title"]){
					$options["/title"]["*"] = $options["title"];
					$buffer->strong($options["/title"]);
				}
				$options["/content"]["*"] = $mixed;
				if($options["inline"]) $options["/content"][".d-inline"] = true;

				$buffer->div($options["/content"]);

			$buffer->_div();

			return $buffer->build();
		});

	}
	//--------------------------------------------------------------------------------
    public function is_empty() {
        return $this->item_arr ? false : true;
    }
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function build($options = []) {

	    $options = array_merge([
	        "list_type" => "ul",
	        ".list-unstyled" => true,
	        ".list-group" => false,
	        ".list-inline" => false,
	        "/item" => [],
	    ], $options, $this->options);

		// init
		$buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();

		if(!$this->item_arr) return false;

		$buffer->{"{$options["list_type"]}_"}($options);
		foreach ($this->item_arr as $key => $item){
			$item = array_merge($options["/item"], $item);

			if($options[".list-inline"]) $item[".list-inline-item"] = true;
			if($options[".list-group"]) $item[".list-group-item"] = true;

		    $buffer->li_($item);

		    	if($item["icon"]) $buffer->xicon($item["icon"]);

		        if(is_callable($item["html"])){
		            $item["html"]($buffer);
                }else{
                    $buffer->add($item["html"]);
                }
		    $buffer->_li();
        }
		$buffer->{"_{$options["list_type"]}"}();


		return $buffer->get_clean();
	}
	//--------------------------------------------------------------------------------
}