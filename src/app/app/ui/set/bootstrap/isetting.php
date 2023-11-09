<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class isetting extends iproperty {
	//--------------------------------------------------------------------------------
	// fields
	//--------------------------------------------------------------------------------
	protected static $is_singleton = true;

    /**
     * @var \LiquidedgeApp\Octoapp\app\app\ui\solid\property_set\solid_classes\settings\intf\standard
     */
	protected $solid;

	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		// init
		$this->name = "Setting Solid input";
	}
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function build($options = []) {
		// options
		$options = array_merge([
			"key" => false,
			"value" => false,
			"label" => false,
			"required" => false,
		], $options);

		$this->solid = \LiquidedgeApp\Octoapp\app\app\ui\solid::get_setting_instance($options["key"]);

		if($options["value"] === false) $options["value"] = $this->solid->get_value(["html_decode" => false]);

		$buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();
		$buffer->xihidden("settings_arr[{$this->solid->get_form_id()}]", $this->solid->get_key());
		$buffer->add($this->build_input($options));

		return $buffer->build();
	}
	//--------------------------------------------------------------------------------
}