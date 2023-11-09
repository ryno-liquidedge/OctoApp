<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * @package mod\ui\set\system
 * @author Ryno Van Zyl
 *
 * http://ionden.com/a/plugins/ion.rangeSlider/api.html
 *
 */
class irange extends \com\ui\intf\element {
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		// init
		$this->name = "Range Input";
	}
	//--------------------------------------------------------------------------------
	public function build($options = []) {

		$options = array_merge([
			"id" => false,
			"label" => false,
			"help" => false,
			"!change" => "function (data) {}",

            "value_from" => 0,
		    "value_to" => 1000,
		    "min" => 0,
		    "max" => 1000,
		    "step" => 1,

		    "/" => [],

		], $options);

		$id = $options["id"];
		$label = $options["label"];
		$onchange = $options["!change"];

		$buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();

		$buffer->div_([".min-h-100px" => true]);
			$buffer->div_([".form-group" => true, "@data-source" => "#{$id}", "#display" => false]);

				$buffer->label(["@for" => $id, "*" => $label]);

				$options["@id"] = $id;
				$options["@type"] = "text";
				$options["@name"] = $id;
				$options[".js-range-slider"] = true;
				unset($options["!change"]);

				$buffer->input($options);

				$js_options = [];
				$js_options["*step"] = $options["step"];
				$js_options["*type"] = "double";
				$js_options["*min"] = $options["min"];
				$js_options["*max"] = $options["max"];
				$js_options["*from"] = $options["value_from"];
				$js_options["*to"] = $options["value_to"];
				$js_options["*grid"] = "true";
				$js_options["*onStart"] = "!function (data) {}";
				$js_options["*onChange"] = "!function (data) {}";
				$js_options["*onFinish"] = "!function (data) {
					let onchange = {$onchange};
					onchange.apply(this, [data]);
				}";
				$js_options["*onUpdate"] = "!function (data) {
					setTimeout(function(){
						$('#". \LiquidedgeApp\Octoapp\app\app\js\js::parse_id("{$id}_input[min]")."').val(data.min);
						$('#". \LiquidedgeApp\Octoapp\app\app\js\js::parse_id("{$id}_input[max]")."').val(data.max);
					}, 250);
				}";

				$js_options = \LiquidedgeApp\Octoapp\app\app\js\js::create_options(array_merge($js_options, $options["/"]));

				\LiquidedgeApp\Octoapp\app\app\js\js::add_script("
					var $id;
					$(function(){
						let el = $('#$id');
						$id = el.ionRangeSlider({$js_options}).data('ionRangeSlider');
					});
				");

				if($options["help"]) $buffer->small(["*" => $options["help"], "@id" => "{$id}Help", ".form-text text-muted" => true]);

			$buffer->_div();
		$buffer->_div();

		$buffer->div_([".d-flex" => true]);
			$buffer->xihidden("{$id}_is_input_search", false);
			$buffer->xitext("{$id}_input[min]", $options["value_from"], false, ["limit" => "numeric", ".form-control-sm {$id}_input_min" => true, "/wrapper" => [".me-1" => true], "@placeholder" => "Min", "!focus" => "$(this).select()", "!enter" => "apply_input_search_{$id}()"]);
			$buffer->xitext("{$id}_input[max]", $options["value_to"], false, ["limit" => "numeric", ".form-control-sm {$id}_input_max" => true, "/wrapper" => [".me-1" => true], "@placeholder" => "Max", "!focus" => "$(this).select()", "!enter" => "apply_input_search_{$id}()"]);
			$buffer->xbutton(false, "apply_input_search_{$id}()", ["icon" => "fa-search", ".btn-sm btn-outline-primary" => true]);
			if($options["min"] != $options["value_from"] || $options["max"] != $options["value_to"]) $buffer->xbutton(false, "reset_search_{$id}()", ["icon" => "fa-refresh", ".btn-sm btn-outline-warning" => true, ".ms-1" => true]);
		$buffer->_div();
		$buffer->script(["*" => "
			function apply_input_search_{$id}(){
				let applyRange = {$onchange}; 
				$('#{$id}_is_input_search').val(1);
				applyRange();
				$('#{$id}_is_input_search').val('');
			}
			
			function reset_search_{$id}(){
				let applyRange = {$onchange}; 
				$('#{$id}_is_input_search').val(1);
				$('#". \LiquidedgeApp\Octoapp\app\app\js\js::parse_id("{$id}_input[min]")."').val('0');
				$('#". \LiquidedgeApp\Octoapp\app\app\js\js::parse_id("{$id}_input[max]")."').val('0');
				applyRange();
				$('#{$id}_is_input_search').val('');
			}
			
		"]);

		return $buffer->build();

	}
	//--------------------------------------------------------------------------------
}