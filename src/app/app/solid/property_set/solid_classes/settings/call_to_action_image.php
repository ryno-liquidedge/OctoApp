<?php
namespace LiquidedgeApp\Octoapp\app\app\solid\property_set\solid_classes\settings;

/**
 * @package app\property_set\product_property
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class call_to_action_image extends \LiquidedgeApp\Octoapp\app\app\solid\property_set\solid_classes\settings\intf\standard {

	//--------------------------------------------------------------------------------
	public function get_display_name() {
		return "Image";
	}
	//--------------------------------------------------------------------------------
	public function get_code() {
		return "SETTING_CALL_TO_ACTION_IMAGE";
	}
	//--------------------------------------------------------------------------------
	public function get_key() {
		return "gs1.setting:CALL_TO_ACTION_IMAGE";
	}
	//--------------------------------------------------------------------------------
	public function get_data_type() {
		return \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_FILE;
	}
	//--------------------------------------------------------------------------------
    public function parse($mixed, $options = []) {
        $value = parent::parse($mixed);
        if(!$value || isnull($value)) return $this->get_default();

        return $value;
    }
    //--------------------------------------------------------------------------------
	public function get_default() {
        return "null";
    }
    //--------------------------------------------------------------------------------
}
