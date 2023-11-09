<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * @package app\ui\set\bootstrap
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class flip_card extends \com\ui\intf\element {
	//--------------------------------------------------------------------------------
	// fields
	//--------------------------------------------------------------------------------
	protected $options = [];

	protected $content_front;
	protected $content_back;

	protected static $is_singleton = true;
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		// init
		$this->name = "Flip Card";

		// options
		$this->options = $options;
	}
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	/**
	 * @param mixed $content_front
	 * @param array $options
	 */
	public function set_content_front($content_front, $options = []): void {

		if(is_callable($content_front)) $content_front = $content_front();

		$this->content_front = $content_front;
		$this->options["/content_front"] = $options;
	}
	//--------------------------------------------------------------------------------

	/**
	 * @param mixed $content_back
	 * @param array $options
	 */
	public function set_content_back($content_back, $options = []): void {

		if(is_callable($content_back)) $content_back = $content_back();

		$this->content_back = $content_back;
		$this->options["/content_back"] = $options;
	}
	//--------------------------------------------------------------------------------
	public function build($options = []) {

		$options = array_merge([
		    "width" => false,
			"height" => false,

			"content_front" => $this->content_front,
			"/content_front" => [],

			"content_back" => $this->content_back,
			"/content_back" => [],

		], $options, $this->options);

		$options["/content_front"][".flip-card-front"] = true;
		$options["/content_back"][".flip-card-back"] = true;

		$buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();
		$buffer->div_([".flip-card" => true, "#width" => $options['width'], "#height" => $options['height']]);
			$buffer->div_([".flip-card-inner" => true]);
				$buffer->div_($options["/content_front"]);
					$buffer->div_([".w-100" => true]);
						$buffer->add($options["content_front"]);
					$buffer->_div();
				$buffer->_div();

				$buffer->div_($options["/content_back"]);
					$buffer->div_([".w-100" => true]);
						$buffer->add($options["content_back"]);
					$buffer->_div();
				$buffer->_div();
			$buffer->_div();
		$buffer->_div();

		// done
		return $buffer->build();
	}
	//--------------------------------------------------------------------------------
}