<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * @package app\ui\set\bootstrap
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class image_card extends \com\ui\intf\element {
	//--------------------------------------------------------------------------------
	// fields
	//--------------------------------------------------------------------------------
	protected $options = [
		"/src" => [],
		"/hover_content" => [
			"html" => false
		],
	];
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		// init
		$this->name = "Image Card";
	}
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	/**
	 * @param $src
	 * @param array $options
	 * @return $this
	 */
	public function set_src($src, $options = []): image_card {

		$options["@src"] = $src;

		$this->options["/src"] = $options;

		return $this;
	}
	//--------------------------------------------------------------------------------
	/**
	 * @param $html
	 * @param array $options
	 * @return $this
	 */
	public function set_hover_content($html, $options = []): image_card {

		$options = array_merge([

			//fade effects
			".from-t" => false, //fade from top
			".from-b" => false, //fade from bottom

			//defaults
			"opacity" => 100, // 100 | 75 | 50
		], $options);

		if(is_callable($html)) $html = $html($this);

		$options["html"] = $html;
		$options[".hover-overlay"] = true;
		$options[".overlay-{$options["opacity"]}"] = true;

		$this->options["/hover_content"] = $options;

		return $this;
	}
	//--------------------------------------------------------------------------------
	public function build($options = []) {
		// options
		$options = array_merge([
  		], $options);

		$buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();

		$has_content = (bool) $this->options["/hover_content"]["html"];
		$options[".hover-fade-in-overlay"] = $has_content;

		$buffer->div_($options);
			$buffer->img($this->options["/src"]);

			if($has_content){
				$buffer->div_($this->options["/hover_content"]);
					$buffer->add($this->options["/hover_content"]["html"]);
				$buffer->_div();
			}

		$buffer->_div();

		return $buffer->build();

	}
	//--------------------------------------------------------------------------------
}