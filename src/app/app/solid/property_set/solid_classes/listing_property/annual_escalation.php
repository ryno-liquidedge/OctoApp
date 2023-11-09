<?php
namespace LiquidedgeApp\Octoapp\app\app\solid\property_set\solid_classes\listing_property;

/**
 * @package app\solid\property_set\solid_classes\listing_property
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class annual_escalation extends \LiquidedgeApp\Octoapp\app\app\solid\property_set\intf\standard {

	//--------------------------------------------------------------------------------
	public function get_display_name() {
		return "Annual Escalation";
	}
	//--------------------------------------------------------------------------------
	public function get_code() {
		return "LISTING_PROPERTY_ANNUAL_ESCALATION";
	}
	//--------------------------------------------------------------------------------
	public function get_key() {
		return "gs1.product:ANNUAL_ESCALATION";
	}
	//--------------------------------------------------------------------------------
	public function get_data_type() {
		return \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_PERCENTAGE;
	}
	//--------------------------------------------------------------------------------
	public function format($mixed, $options = []){
		return \LiquidedgeApp\Octoapp\app\app\num\num::percentage($this->parse($mixed));
	}
    //--------------------------------------------------------------------------------
}
