<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * Class fancybox
 * @package app\ui\set\website
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class fancybox extends \LiquidedgeApp\Octoapp\app\app\ui\intf\element {
	//--------------------------------------------------------------------------------
	// fields
	//--------------------------------------------------------------------------------
    protected $item_arr = [];
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		// init
		$this->name = "Fancybox";
		$this->id = \core::$app->get_session()->session_uid;

		$this->buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();
	}
	//--------------------------------------------------------------------------------
    public function __call($name, $arguments) {
		call_user_func_array([$this->buffer, $name], $arguments);

		return $this->buffer;
	}
	//--------------------------------------------------------------------------------
    public function add_item($src, $src_thumb = false, $options = []) {

	    $options = array_merge([
	        "index" => \LiquidedgeApp\Octoapp\app\app\str\str::get_random_id(),
	        "/thumb" => [".thumbnail zoom" => true],
	    ], $options);

	    if(!$src_thumb) $src_thumb = $src;

	    $options["src"] = $src;
	    $options["src_thumb"] = $src_thumb;

        $this->item_arr[$options["index"]] = $options;
    }
	//--------------------------------------------------------------------------------
    public function build($options = []) {
		$options = array_merge([
		    ".fancy-box-wrapper" => true,
		    ".d-flex flex-wrap" => true,
		    ".justify-content-center" => true,
		], $options);
	    //init

	    $this->buffer->div_($options);
	        $fancybox = \LiquidedgeApp\Octoapp\app\app\ui\inc\fancybox\fancybox::make();
	        foreach ($this->item_arr as $item){
                $fancybox->add_image($item["src"], $item["src_thumb"], $item);
            }
            $this->buffer->add($fancybox->build());
	    $this->buffer->_div();

	    return $this->buffer->get_clean();

    }
	//--------------------------------------------------------------------------------
}