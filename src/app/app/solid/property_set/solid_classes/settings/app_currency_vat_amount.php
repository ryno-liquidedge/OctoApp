<?php
namespace LiquidedgeApp\Octoapp\app\app\solid\property_set\solid_classes\settings;

/**
 * @package app\property_set\product_property
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class app_currency_vat_amount extends \LiquidedgeApp\Octoapp\app\app\solid\property_set\solid_classes\settings\intf\standard {

	//--------------------------------------------------------------------------------
	public function get_display_name() {
		return "VAT Amount";
	}
	//--------------------------------------------------------------------------------
	public function get_code() {
		return "SETTING_APP_CURRENCY_VAT_AMOUNT";
	}
	//--------------------------------------------------------------------------------
	public function get_key() {
		return "gs1.setting:APP_CURRENCY_VAT_AMOUNT";
	}
	//--------------------------------------------------------------------------------
	public function get_data_type() {
		return \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_INT;
	}
	//--------------------------------------------------------------------------------
	public function get_default() {
		return 15;
	}
	//--------------------------------------------------------------------------------
}
