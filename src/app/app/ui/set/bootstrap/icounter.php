<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * @package app\ui\set\bootstrap
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class icounter extends \com\ui\intf\element {
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct() {
		$this->name = "Number Counter";
	}
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function build($options = []) {
		// options
		$options = array_merge([
			"id" => false,
			"value" => 1,
			"min" => 0,
			"max" => null,
			"step" => 1,
			"!change" => false,
			"!down" => false,
			"!leave" => false,
			"/input" => [],
			"/btn" => [],
			"allow_input" => true,
			"color" => "primary",
			"required_skipval" => false,
			"required" => false,
			"/required" => [],
			"required_title" => "Required",
			"label" => false,
			"label_width" => false,
			"label_middle" => true,
			"label_col" => false,
			"label_html" => false,
			"label_click" => false,
			"label_hidden" => false,
			"append" => false,
			"append_type" => false,

			"/form_input" => [],
			"/wrapper" => [],
			"wrapper_id" => \LiquidedgeApp\Octoapp\app\app\str\str::get_random_id("input_counter_"),
		], $options);

		$wrapper_id = $options["wrapper_id"];
		$min = $options["min"];
		$max = $options["max"];
		$value = $options["value"];
		$input_id = \LiquidedgeApp\Octoapp\app\app\str\str::get_random_id($options["id"]);
		if(isempty($value)) $value = 0;

		if($options["label_middle"]) {
		    $options["/wrapper"][".align-items-center"] = true;
        };

		if($min > 0 && $value < $min) $value = $min;

		$buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();

		$buffer->style("*
			.invalid-feedback[data-target]{
				display:block;
			}
		");

		$wrapper_options["@id"] = $wrapper_id;
		$wrapper_options[".qty-input"] = true;

		$buffer->div_($wrapper_options);
			$buffer->span_(array_merge([".minus btn btn-{$options["color"]}" => true], $options["/btn"]));
				$buffer->xicon("fa-minus", [".mr-2" => false]);
			$buffer->_span();

			$options["/input"]["@value"] = $value;
			$options["/input"]["@type"] = "number";
			$options["/input"][".count"] = true;
			$options["/input"]["@id"] = $input_id;
			$options["/input"]["@name"] = $input_id;
			$options["/input"]["@defaultvalue"] = $value;

			$buffer->xihidden($options["id"], $value);
			$buffer->input($options["/input"]);

			$buffer->span_(array_merge([".plus btn btn-{$options["color"]}" => true], $options["/btn"]));
				$buffer->xicon("fa-plus", [".mr-2" => false]);
			$buffer->_span();

		$buffer->_div();

		$buffer->div([".invalid-feedback" => true, "@data-target" => $input_id]);

		// required
		if (!$options["required_skipval"]) {
			if ($options["required"] && \com\ui\helper::$current_form) {
				\com\ui\helper::$current_form->add_validation_zero($input_id, $options["label"]);
			}
		}

		$js_id = \LiquidedgeApp\Octoapp\app\app\js\js::parse_id($options["id"]);

		\com\js::add_script("
			
			var {$input_id} = {
				plus:function(){
					let val = parseInt($('#{$wrapper_id} .count').val()) + {$options["step"]};
					let max = ".(is_null($max) ? 0 : $max).";
					
					if (max > 0 && val >= max)  val = max;
					
				    $('#{$wrapper_id} .count').val(val);
					$('#{$js_id}').val(val);
				},
				minus:function(){
					let val = parseInt($('#{$wrapper_id} .count').val()) - {$options["step"]};
					if (val <= $min)  val = $min;
					
					$('#{$wrapper_id} .count').val(val);
					$('#{$js_id}').val(val);
				},
				change:function(e){
					let val = parseInt($('#{$input_id}').val());
					let min = parseInt($min);
					
					if (val <= min) {
					 	val = min;
						$('#{$input_id}').val(val);
					}
					
					$('#{$js_id}').val(val);
					
					setTimeout(function(){
						{$options["!change"]}
					}, 20);
				}
			};
		
			$(document).ready(function(){
				
				var min = parseInt($min);
				var intervalId = 0;
				var timeoutId = 0;

				$('body').on('click', '#{$input_id}', function(){
				   $(this).select();
				});

				$('body').on('mousedown', '#{$wrapper_id} .plus', function(){
					timeoutId = setTimeout(function(){ 
						{$options["!down"]}
						 intervalId = setInterval(function(){
							{$input_id}.plus();
						}, 70);
					}, 200);
				}).on('mouseup mouseleave', function() {
					clearTimeout(timeoutId);
					clearTimeout(intervalId);
				});
				
				$('body').on('mousedown', '#{$wrapper_id} .minus', function(){
					timeoutId = setTimeout(function(){ 
						{$options["!down"]}
						 intervalId = setInterval(function(){
							{$input_id}.minus();
						}, 70);
					}, 200);
				}).on('mouseup mouseleave', function() {
					clearTimeout(timeoutId);
				});
			
				$('body').on('blur', '#{$input_id}', function(e){
				
					let el = $(this);
					e.preventDefault();
					e.stopPropagation();
					{$input_id}.change(e);
					
					if(el.val() == ''){
						el.val(el.attr('defaultvalue'));
					}
				});
				
				$('body').on('click', '#{$wrapper_id} .plus', function(e){
				
					e.preventDefault();
					e.stopPropagation();
				
					{$input_id}.plus();
				    {$input_id}.change(e);
				});
				
				$('body').on('click', '#{$wrapper_id} .minus', function(e){
				
					e.preventDefault();
					e.stopPropagation();
				
					{$input_id}.minus();
					{$input_id}.change(e);
					
				});
				
				".(!$options["allow_input"] ? "$('#{$input_id}').prop('disabled', true);" : "")."
				
			});
		");


		if($options["label"]){
			$wrapper_buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();

			$form_input_options = array_merge([

				"label" => $options["label"],
				"label_width" => $options["label_width"],
				"label_col" => $options["label_col"],
				"label_html" => $options["label_html"],
				"label_click" => $options["label_click"],

				"required" => $options["required"],
				"/required" => $options["/required"],
				"required_title" => $options["required_title"],
				"wrapper_id" => $options["wrapper_id"],
				"append" => $options["append"],
				"append_type" => $options["append_type"],
				"/wrapper" => $options["/wrapper"],

			], $options["/form_input"]);

			$wrapper_buffer->xform_input(function($html)use($buffer){
				$html->add($buffer->get_clean());
			}, $form_input_options);

			return $wrapper_buffer->build();
		}

		return $buffer->get_clean();
	}
	//--------------------------------------------------------------------------------
}