<?php
namespace LiquidedgeApp\Octoapp\app\app\solid\property_set\solid_classes\settings;

/**
 * @package app\property_set\product_property
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class website_title extends \LiquidedgeApp\Octoapp\app\app\solid\property_set\solid_classes\settings\intf\standard {

	//--------------------------------------------------------------------------------
	public function get_display_name() {
		return "Website Title";
	}
	//--------------------------------------------------------------------------------
	public function get_code() {
		return "SETTING_WEBSITE_TITLE";
	}
	//--------------------------------------------------------------------------------
	public function get_key() {
		return "gs1.setting:WEBSITE_TITLE";
	}
	//--------------------------------------------------------------------------------
	public function get_data_type() {
		return \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_STRING;
	}
	//--------------------------------------------------------------------------------
	public function get_default() {
        return \core::$app->get_instance()->get_company();
    }
    //--------------------------------------------------------------------------------
}
