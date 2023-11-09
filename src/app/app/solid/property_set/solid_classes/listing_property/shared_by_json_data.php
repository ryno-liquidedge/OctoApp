<?php
namespace LiquidedgeApp\Octoapp\app\app\solid\property_set\solid_classes\listing_property;

/**
 * @package app\solid\property_set\solid_classes\listing_property
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class shared_by_json_data extends \LiquidedgeApp\Octoapp\app\app\solid\property_set\intf\standard {

	//--------------------------------------------------------------------------------
	public function get_display_name() {
		return "Shared By";
	}
	//--------------------------------------------------------------------------------
	public function get_code() {
		return "LISTING_PROPERTY_SHARED_BY_PERSON";
	}
	//--------------------------------------------------------------------------------
	public function get_key() {
		return "gs1.product.share:BY_PERSON";
	}
	//--------------------------------------------------------------------------------
	public function get_data_type() {
		return \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_JSON;
	}
	//--------------------------------------------------------------------------------
	public function format($mixed, $options = []) {
		$mixed = stripslashes(html_entity_decode($mixed));
		return json_decode($mixed);
	}
	//--------------------------------------------------------------------------------
}
