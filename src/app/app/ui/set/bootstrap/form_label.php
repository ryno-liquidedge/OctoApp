<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * @package app\ui\set\bootstrap
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class form_label extends \com\ui\intf\element {
	//--------------------------------------------------------------------------------
	// fields
	//--------------------------------------------------------------------------------
	protected static $is_singleton = true;
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		// init
		$this->name = "Form label";
	}
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function build($options = []) {
		//--------------------
		// options
		$options = array_merge([
			"label" => false,
			"for" => false,

			"required" => false,
			"required_title" => "Required",
			"/required" => [],
			"required_icon" => true,
			"html" => true,
		], $options);

		// large screens
		if (\LiquidedgeApp\Octoapp\app\app\ui\ui::$is_xl) {
			$options[".col-xl-2"] = true;
		}
		$options[".d-flex"] = true;
		$options[".ui-form-label"] = true;


		if(!$options["label"]) return;

		//--------------------
		// html
		$html = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();
		$html->label_($options);
		{
			// label
			$html->content($options["label"], ["html" => $options["html"]]);

			// required
			if ($options["required"] && $options["required_icon"]) {
				$html->span_(["@title" => $options["required_title"]]);
				{
					$html->xicon("exclamation-circle", array_merge([".ms-2" => true, ".text-warning" => true, ".field-required-icon" => true], $options["/required"]));
				}
				$html->_span();
				\LiquidedgeApp\Octoapp\app\app\ui\ui::make()->tooltip();
			}
		}
		$html->_label();

		//--------------------
		// done
		return $html->get_clean();
	}
	//--------------------------------------------------------------------------------
}