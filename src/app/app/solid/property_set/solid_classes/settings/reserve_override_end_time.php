<?php
namespace LiquidedgeApp\Octoapp\app\app\solid\property_set\solid_classes\settings;

/**
 * @package app\property_set\product_property
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class reserve_override_end_time extends \LiquidedgeApp\Octoapp\app\app\solid\property_set\solid_classes\settings\intf\standard {

	//--------------------------------------------------------------------------------
	public function get_display_name() {
		return "Reserve Override End Time";
	}
	//--------------------------------------------------------------------------------
	public function get_code() {
		return "SETTING_RESERVE_OVERRIDE_END_TIME";
	}
	//--------------------------------------------------------------------------------
	public function get_key() {
		return "gs1.setting:RESERVE_OVERRIDE_END_TIME";
	}
	//--------------------------------------------------------------------------------
	public function get_data_type() {
		return \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_STRING;
	}
	//--------------------------------------------------------------------------------
}
