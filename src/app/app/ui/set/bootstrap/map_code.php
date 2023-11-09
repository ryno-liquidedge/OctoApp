<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * @package app\ui\set\bootstrap
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class map_code extends \com\ui\intf\element {
	//--------------------------------------------------------------------------------
	// fields
	//--------------------------------------------------------------------------------

	private $width = "100%";
	private $height = "450px";

	private array $marker = [];
	private array $options = [];

	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		$this->options = $options;
	}
	//--------------------------------------------------------------------------------
	public function build($options = []) {

		$options = array_merge([
			"@id" => \LiquidedgeApp\Octoapp\app\app\str\str::get_random_id("map"),
			"html" => false,
			"width" => $this->width,
			"height" => $this->height,
            ".google-map-wrapper" => true
		], $options, $this->options);


		if(!$options["html"]) return "";

		$buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();
		$buffer->style(["*" => "
		    #{$options["@id"]} iframe, #{$options["@id"]} .map-loader{
		        width: {$options["width"]};
		        height: {$options["height"]};
		    }
		"]);
		$buffer->div_($options);
			$buffer->div_([".map-loader d-flex align-items-center justify-content-center" => true]);
                $buffer->span(["*" => "Loading Map...", ".me-2" => true])->xicon("fa-spinner", [".fa-spin" => true]);
            $buffer->_div();
            $buffer->div_([".wrapper" => true]);
				$buffer->add($options["html"]);
            $buffer->_div();
		$buffer->_div();
		$buffer->script(["*" => "
			$(function(){
				$('#{$options["@id"]} iframe').css('height', '0px');
				$('#{$options["@id"]} iframe').on('load', function(){
					$('.map-loader').hide().removeClass('d-flex');
					$(this).css('height', '{$options["height"]}');
				});
			});
		"]);

		return $buffer->build();
	}
	//--------------------------------------------------------------------------------

	/**
	 * @param string $width
	 */
	public function set_width(string $width): void {
		$this->width = $width;
	}
	//--------------------------------------------------------------------------------

	/**
	 * @param string $height
	 */
	public function set_height(string $height): void {
		$this->height = $height;
	}
	//--------------------------------------------------------------------------------
}