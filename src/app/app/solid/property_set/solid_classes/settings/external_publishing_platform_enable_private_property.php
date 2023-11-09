<?php
namespace LiquidedgeApp\Octoapp\app\app\solid\property_set\solid_classes\settings;

/**
 * @package app\property_set\product_property
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class external_publishing_platform_enable_private_property extends \LiquidedgeApp\Octoapp\app\app\solid\property_set\solid_classes\settings\intf\standard {

	//--------------------------------------------------------------------------------
	public function get_display_name() {
		return "Enable Private Property Platform";
	}
	//--------------------------------------------------------------------------------
	public function get_code() {
		return "SETTING_EXTERNAL_PUBLISHING_PLATFORM_ENABLE_PRIVATE_PROPERTY";
	}
	//--------------------------------------------------------------------------------
	public function get_key() {
		return "gs1.setting:EXTERNAL_PUBLISHING_PLATFORM_ENABLE_PRIVATE_PROPERTY";
	}
	//--------------------------------------------------------------------------------
	public function get_data_type() {
		return \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_BOOL;
	}
    //--------------------------------------------------------------------------------
}
