<?php
namespace LiquidedgeApp\Octoapp\app\app\solid\property_set\solid_classes\settings;

/**
 * @package app\property_set\product_property
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class company_email_test_cc extends \LiquidedgeApp\Octoapp\app\app\solid\property_set\solid_classes\settings\intf\standard {

	//--------------------------------------------------------------------------------
	public function get_display_name() {
		return "Test Email CC's (Comma Separated)";
	}
	//--------------------------------------------------------------------------------
	public function get_code() {
		return "SETTING_COMPANY_EMAIL_TEST_CC";
	}
	//--------------------------------------------------------------------------------
	public function get_key() {
		return "gs1.setting:COMPANY_EMAIL_TEST_CC";
	}
	//--------------------------------------------------------------------------------
	public function get_data_type() {
		return \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_TEXT;
	}
    //--------------------------------------------------------------------------------
	public function format($mixed, $options = []) {

		$email_arr = explode(",", $mixed);

		foreach ($email_arr as $index => $email){
			$email_arr[$index] = trim($email);
		}

		$email_arr = array_filter($email_arr);

		return $email_arr;
	}
	//--------------------------------------------------------------------------------
}
