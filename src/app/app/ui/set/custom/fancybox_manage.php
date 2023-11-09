<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\custom;

/**
 * Class fancybox
 * @package app\ui\set\website
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class fancybox_manage extends \LiquidedgeApp\Octoapp\app\app\ui\intf\element {
	//--------------------------------------------------------------------------------
	// fields
	//--------------------------------------------------------------------------------
	/**
	 * @var \com\ui\intf\buffer
	 */
    protected $buffer;

	/**
	 * @var array
	 */
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
	        "/thumb" => [".thumbnail" => true],
	        "content" => false,
	        "hover_content" => false,
	    ], $options);

	    if(!$src_thumb) $src_thumb = $src;

	    if(is_callable($options["content"])) $options["content"] = $options["content"]();
	    if(is_callable($options["hover_content"])) $options["hover_content"] = $options["hover_content"]();

	    $options["src"] = $src;
	    $options["src_thumb"] = $src_thumb;

        $this->item_arr[] = $options;
    }
	//--------------------------------------------------------------------------------
    public function build($options = []) {
		$options = array_merge([
		    ".fancy-box-wrapper" => true,
		    ".d-flex flex-wrap justify-content-center justify-content-md-start" => true,
		], $options);
	    //init

	    $this->buffer->div_($options);

	    foreach ($this->item_arr as $item){
	    	$item[".fancybox-manage-item"] = true;
	    	$item[".hover-effect"] = (bool)$item["hover_content"];
	    	$this->buffer->div_($item);
	    		if(!$item["hover_content"]){
	    			$this->buffer->a_(["@data-fancybox" => "gallery", "@href" => $item["src"]]);
				}
	    		$item["/thumb"][".img-fluid"] = true;
				$this->buffer->ximage($item["src_thumb"], $item["/thumb"]);
	    		if(!$item["hover_content"]){
	    			$this->buffer->_a();
				}

				if($item["content"]) {
					$this->buffer->add($item["content"]);
				}
				if($item["hover_content"]){
					$this->buffer->div_([".overlay d-flex justify-content-center align-items-center" => true]);
						$this->buffer->div_([".overlay-content" => true]);
							$this->buffer->add($item["hover_content"]);
						$this->buffer->_div();
					$this->buffer->_div();
				}
			$this->buffer->_div();
		}

	    $this->buffer->_div();

	    return $this->buffer->build();

    }
	//--------------------------------------------------------------------------------
}