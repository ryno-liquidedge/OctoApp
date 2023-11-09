<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * Class card_carousel
 * @package app\ui\set\bootstrap
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class legend extends \com\ui\intf\element {
	//--------------------------------------------------------------------------------
	// fields
	//--------------------------------------------------------------------------------
    protected $item_arr = [];
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		// init
		$this->name = "Legend";
		$this->id = \core::$app->get_session()->session_uid;
	}
	//--------------------------------------------------------------------------------
    public function add_item($label, $color, $options = []) {

        $options = array_merge([
            "icon" => false,
            "color" => $color,
            "label" => $label,
            "/li" => [],
        ], $options);

        $this->item_arr[] = $options;
    }
	//--------------------------------------------------------------------------------
	public function is_empty() {
		return !(bool) $this->item_arr;
	}
	//--------------------------------------------------------------------------------
    public function build($options = []) {

		$options = array_merge([
			"@id" => $this->id,
			".ui-legend list-inline" => true,
			"square_format" => false,
			"square_size" => 20,
		    "/li" => []
		], $options);

	    //init
	    $buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();
	    $buffer->ul_($options);

        foreach ($this->item_arr as $key => $item){
        	$item = array_merge($item, $options["/li"]);
			$item["/li"][".list-inline-item"] = true;
            $buffer->li_($item["/li"]);
            	if($options["square_format"]){
            		$buffer->div_([".d-flex align-items-center" => true]);
						$item["#width"] = "{$options["square_size"]}px";
						$item["#height"] = "{$options["square_size"]}px";
						$item[".me-2"] = true;
						$buffer->xbadge("&nbsp", $item["color"], $item)->span(["*" => $item["label"]]);
            		$buffer->_div();
				}else{
					$buffer->xbadge($item["label"], $item["color"], $item);
				}
            $buffer->_li();
        }

	    $buffer->_ul();

	    return $buffer->get_clean();

    }
	//--------------------------------------------------------------------------------
}