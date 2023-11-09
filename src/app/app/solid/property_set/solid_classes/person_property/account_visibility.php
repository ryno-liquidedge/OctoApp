<?php
namespace LiquidedgeApp\Octoapp\app\app\solid\property_set\solid_classes\person_property;

/**
 * @package app\property_set\solid_classes\person
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

define("ACCOUNT_VISIBILITY_ME"        			, 1);
define("ACCOUNT_VISIBILITY_EVERYONE"   			, 2);
define("ACCOUNT_VISIBILITY_LINKED_TO_PROPERTY"	, 3);

define("ACCOUNT_VISIBILITY_ARRAY" , [
	ACCOUNT_VISIBILITY_EVERYONE => "Everyone",
	ACCOUNT_VISIBILITY_ME => "Me",
	ACCOUNT_VISIBILITY_LINKED_TO_PROPERTY => "Linked to Property",
]);

class account_visibility extends \LiquidedgeApp\Octoapp\app\app\solid\property_set\intf\standard {

	//--------------------------------------------------------------------------------
	public function get_display_name() {
		return "Account Visibility";
	}
	//--------------------------------------------------------------------------------
	public function get_code() {
		return "PERSON_PROPERTY_ACCOUNT_VISIBILITY";
	}
	//--------------------------------------------------------------------------------
	public function get_key() {
		return "gs1.person.property:ACCOUNT_VISIBILITY";
	}
	//--------------------------------------------------------------------------------
	public function get_data_type() {
		return \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_ENUM;
	}
	//--------------------------------------------------------------------------------
	public function parse($mixed, $options = []) {
		return \LiquidedgeApp\Octoapp\app\app\data\data::parse($mixed, \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_STRING, $options);
	}
	//--------------------------------------------------------------------------------
	public function get_data_arr(): array {
		return ACCOUNT_VISIBILITY_ARRAY;
	}
    //--------------------------------------------------------------------------------
	public function get_default() {
		return "";
	}
	//--------------------------------------------------------------------------------
}
