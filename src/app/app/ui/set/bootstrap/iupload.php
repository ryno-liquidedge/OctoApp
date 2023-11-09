<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * @package app\ui\set\bootstrap
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class iupload extends \com\ui\intf\element {
	//--------------------------------------------------------------------------------
	// fields
	//--------------------------------------------------------------------------------
    protected $file_item;

    protected $action_arr = [];
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct() {
		$this->name = "Input Upload";
	}
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
    public function set_file_item($file_item, $options = []) {
	    $this->file_item = array_merge([
	        "file_item" => $file_item,
            ".crop-image" => true,
	    ], $options);
    }
	//--------------------------------------------------------------------------------
    public function add_action($label, $onclick, $options = []) {
        $this->action_arr[] = array_merge([
            "*" => $label,
            "!click" => $onclick,
            ".dropdown-item cursor-pointer" => true,
        ], $options);
    }
	//--------------------------------------------------------------------------------
	public function build($options = []) {
		// options
		$options = array_merge([
		    "id" => \LiquidedgeApp\Octoapp\app\app\str\str::get_random_id(),
		    "/image" => [],
		], $options);

		$buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();
            $buffer->div_([".dropdown dropend image-dropdown" => true, ]);
                $buffer->a_([".dropdown-toggle cursor-pointer" => true, "@type" => "button", "@id" => $options["id"], "@data-bs-toggle" => "dropdown", "@aria-expanded" => "false", ]);
                    $buffer->ximage(\LiquidedgeApp\Octoapp\app\app\http\http::get_stream_url($this->file_item["file_item"]), $this->file_item);
                $buffer->_a();
                $buffer->ul_([".dropdown-menu dropdown-menu-dark" => true, "@aria-labelledby" => $options["id"], ]);
                    foreach ($this->action_arr as $action){
                        $buffer->li_();
                            $buffer->a($action);
                        $buffer->_li();
                    }
                $buffer->_ul();
            $buffer->_div();
            return $buffer->build();
	}
	//--------------------------------------------------------------------------------
}