<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class itext_property extends \com\ui\intf\element {
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	public function __construct($options = []) {
		// init
        $this->name = "Input Text from Property";
	}
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function build($options = []) {

		$buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();
		$solid_class = \LiquidedgeApp\Octoapp\app\app\ui\solid::get_setting_instance($options["key"]);

        $options = array_merge([
            "label" => $solid_class->get_display_name(),
            "default" => $solid_class->get_default(),
            "value" => false,

            "/wrapper" => [".align-items-center" => true]
        ], $options);

        $value = $options["value"];
        if(!$value) $value = $options["default"];

        $buffer->xihidden("settings_arr[{$solid_class->get_form_id()}]", $solid_class->get_form_id());

        // display input based on db field type
		switch ($solid_class->get_data_type()) {

  			case \com\data::TYPE_STRING:
  			    $buffer->xitext($solid_class->get_form_id(), $value, $options["label"], $options);
                break;

            case \com\data::TYPE_TEXT:
            case \com\data::TYPE_HTML:
                $rows = isset($options["rows"]) ? $options["rows"] : 5;
  			    $buffer->xitextarea($solid_class->get_form_id(), $value, $options["label"], $rows, $options);
                break;
		}


        return $buffer->build();
	}
	//--------------------------------------------------------------------------------
}