<?php
namespace LiquidedgeApp\Octoapp\app\app\solid\property_set\solid_classes\settings;

/**
 * @package app\property_set\product_property
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class company_email_accounts extends \LiquidedgeApp\Octoapp\app\app\solid\property_set\solid_classes\settings\intf\standard {

	//--------------------------------------------------------------------------------
	public function get_display_name() {
		return "Company Email Accounts";
	}
	//--------------------------------------------------------------------------------
	public function get_code() {
		return "SETTING_COMPANY_EMAIL_ACCOUNTS";
	}
	//--------------------------------------------------------------------------------
	public function get_key() {
		return "gs1.setting:COMPANY_EMAIL_ACCOUNTS";
	}
	//--------------------------------------------------------------------------------
	public function get_data_type() {
		return \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_STRING;
	}
	//--------------------------------------------------------------------------------
	public function get_default() {
        return \db_settings::get_value(SETTING_COMPANY_EMAIL);
    }
    //--------------------------------------------------------------------------------
}
