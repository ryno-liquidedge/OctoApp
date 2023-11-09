<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class breadcrumb extends \com\ui\intf\element {
	//--------------------------------------------------------------------------------
	// properties
	//--------------------------------------------------------------------------------
    protected $item_arr = [];
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	public function __construct($options = []) {
		// init
        $this->name = "Breadcrumb";
	}
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
    public function add_item($title, $link, $options = []) {
        $this->item_arr[] = array_merge([
            "title" => $title,
            "link" => $link,
            "/breadcrumb-item" => [],
        ], $options);
    }
	//--------------------------------------------------------------------------------
	public function build($options = []) {

	    $options = array_merge([
	        ".breadcrumb" => true
	    ], $options);

		// init
        $buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();
        $buffer->ol_($options);
            foreach ($this->item_arr as $item){
                $item["/breadcrumb-item"][".breadcrumb-item"] = true;
                $buffer->li_($item["/breadcrumb-item"]);
                    $buffer->xlink($item["link"], $item["title"], $item);
                $buffer->_li();
            }
        $buffer->_ol();

		return $buffer->get_clean();
	}
	//--------------------------------------------------------------------------------
}