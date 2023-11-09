<?php
namespace LiquidedgeApp\Octoapp\app\app\solid\property_set\solid_classes\settings;

/**
 * @package app\property_set\product_property
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class mailchimp_api_key extends \LiquidedgeApp\Octoapp\app\app\solid\property_set\solid_classes\settings\intf\standard {

	//--------------------------------------------------------------------------------
	public function get_display_name() {
		return "Mailchimp API Key";
	}
	//--------------------------------------------------------------------------------
	public function get_description(): string {
		return implode("\n", [
			"Log into mailchimp account",
			"Go to https://us1.admin.mailchimp.com/account/api",
			"Scroll down and add API key",
		]);
	}
	//--------------------------------------------------------------------------------
	public function get_code() {
		return "SETTING_MAILCHIMP_API_KEY";
	}
	//--------------------------------------------------------------------------------
	public function get_key() {
		return "gs1.setting:MAILCHIMP_API_KEY";
	}
	//--------------------------------------------------------------------------------
	public function get_data_type() {
		return \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_STRING;
	}
	//--------------------------------------------------------------------------------
}
