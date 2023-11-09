<?php
namespace LiquidedgeApp\Octoapp\app\app\solid\property_set\solid_classes\settings;

/**
 * @package app\property_set\product_property
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class company_tellnr_general extends \LiquidedgeApp\Octoapp\app\app\solid\property_set\solid_classes\settings\intf\standard {

	//--------------------------------------------------------------------------------
	public function get_display_name() {
		return "Company General #";
	}
	//--------------------------------------------------------------------------------
	public function get_code() {
		return "SETTING_COMPANY_TELLNR_GENERAL";
	}
	//--------------------------------------------------------------------------------
	public function get_key() {
		return "gs1.setting:COMPANY_TELLNR_GENERAL";
	}
	//--------------------------------------------------------------------------------
	public function get_data_type() {
		return \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_STRING;
	}
	//--------------------------------------------------------------------------------
	public function get_default() {
        return \db_settings::get_value(SETTING_COMPANY_TELLNR_CONTACT, ["default" => \core::$app->get_instance()->get_option("tell.nr.contact")]);
    }
    //--------------------------------------------------------------------------------
}
