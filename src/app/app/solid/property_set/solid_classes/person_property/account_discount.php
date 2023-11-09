<?php
namespace LiquidedgeApp\Octoapp\app\app\solid\property_set\solid_classes\person_property;

/**
 * @package app\property_set\solid_classes\person
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class account_discount extends \LiquidedgeApp\Octoapp\app\app\solid\property_set\intf\standard {

	//--------------------------------------------------------------------------------
	public function get_display_name() {
		return "Account Discount";
	}
	//--------------------------------------------------------------------------------
	public function get_code() {
		return "PERSON_PROPERTY_ACCOUNT_DISCOUNT";
	}
	//--------------------------------------------------------------------------------
	public function get_key() {
		return "urn:gs1:gdd:cl:AllowanceChargeTypeCode:DI_PERSON";
	}
	//--------------------------------------------------------------------------------
	public function get_data_type() {
		return \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_FLOAT;
	}
	//--------------------------------------------------------------------------------
}
