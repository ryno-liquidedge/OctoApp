<?php
namespace LiquidedgeApp\Octoapp\app\app\solid\property_set\solid_classes\listing_property;

/**
 * @package app\solid\property_set\solid_classes\listing_property
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class feature_is_environmentally_friendly extends \LiquidedgeApp\Octoapp\app\app\solid\property_set\intf\standard {

	//--------------------------------------------------------------------------------
	public function get_display_name() {
		return "Environmentally Friendly";
	}
	//--------------------------------------------------------------------------------
	public function get_code() {
		return "LISTING_PROPERTY_FEATURE_IS_ENVIRONMENTALLY_FRIENDLY";
	}
	//--------------------------------------------------------------------------------
	public function get_key() {
		return "gs1.product:FEATURE:IS_ENVIRONMENTALLY_FRIENDLY";
	}
	//--------------------------------------------------------------------------------
	public function get_data_type() {
		return \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_BOOL;
	}
    //--------------------------------------------------------------------------------
}
