<?php
namespace LiquidedgeApp\Octoapp\app\app\solid\property_set\solid_classes\person_property;

/**
 * @package app\property_set\solid_classes\person
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class tenant_current_rental_amount extends \LiquidedgeApp\Octoapp\app\app\solid\property_set\intf\standard {

	//--------------------------------------------------------------------------------
	public function get_display_name() {
		return "Current Rental Amount";
	}
	//--------------------------------------------------------------------------------
	public function get_code() {
		return "PERSON_PROPERTY_TENANT_CURRENT_RENTAL_AMOUNT";
	}
	//--------------------------------------------------------------------------------
	public function get_key() {
		return "gs1.person.property:TENANT_CURRENT_RENTAL_AMOUNT";
	}
	//--------------------------------------------------------------------------------
	public function get_data_type() {
		return \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_CURRENCY;
	}
	//--------------------------------------------------------------------------------
	public function format($mixed, $options = []){
		return \LiquidedgeApp\Octoapp\app\app\num\num::currency($this->parse($mixed));
	}
	//--------------------------------------------------------------------------------
}
