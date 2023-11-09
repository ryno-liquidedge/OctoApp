<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * @package app\ui\set\bootstrap
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class form_input extends \com\ui\set\bootstrap\form_input {
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function build($options = []) {
		/*
	 * Retuns the basic form element structure. Label and input.
	 *
	 * @param string|function $input <p>Text to use as input or callback function that will render anything within the input structure.</p>
	 *
	 * @param string $options[help] <p>Text to display that is meant to give hints on how to use the element.</p>
	 * @param boolean $options[required] <p>Make the field required. It will be checked for a value when the form is submitted.</p>
	 * @param string|function $options[prepend] <p>Text to add in a special block attached to the front of the element. A callback function may be used to add other controls and advanced text.</p>
	 * @param string|function $options[append] <p>Text to add in a special block attached to the end of the element. A callback function may be used to add other controls and advanced text.</p>
	 * @param string $options[wrapper_id] <p>The id to use on the wrapper form control. Used to hide/show label and input group.</p>
	 * @param string $options[input_append_id] <p></p>
	 * @param int|string $options[label_width] <p></p>
	 * @param boolean $options[label_html] <p></p>
	 *
	 * @return string <p>The form structure HTML.</p>
	 */
	//public static function form_input($input, $options = []) {
		//--------------------
		// options
		$options = array_merge([
			"input" => false,

			"width" => false,
			"help" => false,
			"help_popover" => false,
			"popover_target" => \LiquidedgeApp\Octoapp\app\app\str\str::get_random_id("popover"),
			"required" => false,
			"required_title" => "Required",
			"required_message" => true,
			"required_icon" => true,
			"/required" => [],

			"prepend" => false,
			"prepend_type" => false,
			"append" => false,
			"append_type" => false,

			"label" => false,
			"label_click" => false,
			"label_col" => false,
			"label_html" => true,
			"label_width" => false,
			"label_tooltip" => false,
			"floating_label" => false,
			"/label" => [],
			"/form_label" => [],

			".mb-2" => false,
			"wrapper_id" => false,
			"wrapper_space" => true,
			"/wrapper" => [],
			"/input_group" => [],
			"input_id" => false,
			"input_append_id" => false,
			"horizontal" => true,
		], $options);

		//--------------------
		// sections
		$has_label = ($options["label"] !== false);
		$has_form_group = $has_label;
		$has_col = $has_form_group;
		$has_prepend = (bool)$options["prepend"];
		$has_append = (bool)$options["append"];
		$has_input_group = ($has_prepend || $has_append);
		$has_required = $options["required"];
		$has_required_message = $options["required_message"];
		$has_form_text = $options["help"];
		$label_col = $options["label_col"];
		$is_floating_label = $options["floating_label"];

		if($is_floating_label){
			$has_col = false;
			$has_label = false;
			$options["/wrapper"][".form-floating"] = true;
		}

		if($options["help_popover"]){
			if (is_array($options["help"])) $help = implode("\n", $options["help"]);
			else $help = $options["help"];

			$options["/wrapper"]["@data-bs-toggle"] = "popover";
			$options["/wrapper"]["@data-bs-trigger"] = "hover";
			$options["/wrapper"]["@data-bs-content"] = nl2br($help);
			$options["/wrapper"][".{$options["popover_target"]}"] = true;

		}

		if($options[".mb-2"] && !isset($options["/wrapper"][".mb-2"])){
			$options["/wrapper"][".mb-2"] = true;
        }

		//--------------------
		// html
		$html = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();
		
		//--------------------
		// form-group..
        if($options["wrapper_id"]) $options["/wrapper"]["@id"] = $options["wrapper_id"];
		if ($has_form_group) {

		    if($options["horizontal"]) $options["/wrapper"][".row"] = true;

			$html->div_(".form-group", $options["/wrapper"]);
		}else if($options["/wrapper"]){
		    $html->div_($options["/wrapper"]);
        }
		
		//--------------------
		// label
		if ($has_label) {
			// label link
			if ($options["label_click"]) {
				$options["label_html"] = true;
				// dropdown
				if ($options["label_click"] instanceof \com\ui\intf\dropdown) {
					$options["label"] = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->link($options["label_click"], $options["label"]);
				}
				else {
					$options["label"] = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->link(false, $options["label"], ["!click" => $options["label_click"]." return false;"]);
				}
			}
			
			// label
			$col = $label_col ? ".col-12 col-lg-{$label_col}" : ".col-12 col-lg-3";
			$html->xform_label($options["label"], $options["input_id"], array_merge([
				".col-form-label" => $has_col,
				$col => $has_col,
				"required" => $options["required"],
				"required_title" => $options["required_title"],
				"/required" => $options["/required"],
				"required_icon" => $options["required_icon"],
				"html" => $options["label_html"],
				"@title" => $options["label_tooltip"],
			], $options["/label"], $options["/form_label"]));
		}
		
		//--------------------
		// col..
		if ($has_col) {
			$html->div_(".col-md");
		}
		
		//--------------------
		// input-group..
		if ($has_input_group) {
			// width
			$group_options = array_merge([
				"@id" => $options["input_append_id"],
				"width" => $options["width"],
			], $options["/input_group"]);
			$this->apply_options($group_options);

			$html->div_(".input-group", $group_options);
		}
		
		//--------------------
		// prepend
		if ($has_prepend) {
			$html->div_(".input-group-prepend");
			{
				// prepend text group..
				if (!$options["prepend_type"]) {
					$html->span_(".input-group-text");
				}
				
				// content
				if (is_string($options["prepend"])) {
					$html->content($options["prepend"], ["html" => true]);
				}
				elseif (is_callable($options["prepend"])) {
					$options["prepend"]($html);
				}
				
				// ..prepend text group
				if (!$options["prepend_type"]) {
					$html->_span();
				}
			}
			$html->_div();
		}
		
		//--------------------
		// input
		if (is_string($options["input"])) {
			$html->content($options["input"], ["html" => true]);
		}
		elseif (is_callable($options["input"])) {
			$options["input"]($html);
		}
		
		//--------------------
		if($is_floating_label){
			$html->xform_label($options["label"], $options["input_id"], array_merge([
				"required" => $options["required"],
				"required_title" => $options["required_title"],
				"/required" => $options["/required"],
				"required_icon" => $options["required_icon"],
				"html" => $options["label_html"],
				"@title" => $options["label_tooltip"],
			], $options["/label"], $options["/form_label"]));
		}
		//--------------------
		// append
		if ($has_append) {
			$html->div_(".input-group-append");
			{
				// append text group..
				if (!$options["append_type"]) {
					$html->span_(".input-group-text");
				}
				
				// content
				if (is_string($options["append"])) {
					$html->content($options["append"], ["html" => true]);
				}
				elseif (is_callable($options["append"])) $options["append"]($html);
				
				// ..append text group
				if (!$options["append_type"]) {
					$html->_span();
				}
			}
			$html->_div();
		}
		
		//--------------------
		// required
		if ($has_required && $has_required_message) {
			$html->div(".invalid-feedback");
		}
		
		//--------------------
		// ..input-group
		if ($has_input_group) {
			$html->_div();
		}
		
		//--------------------
		// form-text
		if ($has_form_text && !$options["help_popover"]) {
			if (is_array($options["help"])) $help = implode("\n", $options["help"]);
			else $help = $options["help"];
			$html->small(".form-text .text-muted *{$help}", ["br" => true]);
		}

		if($options["help_popover"]){
			$html->script(["*" => "
				$(function(){
					var popover = new bootstrap.Popover(document.querySelector('.{$options["popover_target"]}'), {
					  container: 'body'
					});
				});
			"]);
		}
		
		//--------------------
		// ..col
		if ($has_col) {
			$html->_div();
		}

		//--------------------
		// ..form-group
        if($options["/wrapper"])$html->_div();

		//--------------------
		// done
		return $html->get_clean();
	}
	//--------------------------------------------------------------------------------
}