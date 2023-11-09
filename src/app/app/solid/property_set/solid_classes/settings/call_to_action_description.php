<?php
namespace LiquidedgeApp\Octoapp\app\app\solid\property_set\solid_classes\settings;

/**
 * @package app\property_set\product_property
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class call_to_action_description extends \LiquidedgeApp\Octoapp\app\app\solid\property_set\solid_classes\settings\intf\standard {

	//--------------------------------------------------------------------------------
	public function get_display_name() {
		return "Description";
	}
	//--------------------------------------------------------------------------------
	public function get_code() {
		return "SETTING_CALL_TO_ACTION_DESCRIPTION";
	}
	//--------------------------------------------------------------------------------
	public function get_key() {
		return "gs1.setting:CALL_TO_ACTION_DESCRIPTION";
	}
	//--------------------------------------------------------------------------------
	public function get_data_type() {
		return \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_TEXT;
	}
	//--------------------------------------------------------------------------------
	public function get_default() {
        return "";
    }
    //--------------------------------------------------------------------------------
}
