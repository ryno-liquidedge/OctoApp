<?php
namespace LiquidedgeApp\Octoapp\app\app\solid\property_set\solid_classes\listing_property;

/**
 * @package app\solid\property_set\solid_classes\listing_property
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class date_on_show extends \LiquidedgeApp\Octoapp\app\app\solid\property_set\intf\standard {

	//--------------------------------------------------------------------------------
	public function get_display_name() {
		return "Date on show";
	}
	//--------------------------------------------------------------------------------
	public function get_code() {
		return "LISTING_PROPERTY_DATE_ON_SHOW";
	}
	//--------------------------------------------------------------------------------
	public function get_key() {
		return "gs1.product.marketing:DATE_ON_SHOW";
	}
	//--------------------------------------------------------------------------------
	public function get_data_type() {
		return \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_DATE;
	}
    //--------------------------------------------------------------------------------
}