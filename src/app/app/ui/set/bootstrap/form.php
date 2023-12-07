<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class form extends \com\ui\intf\element {
	//--------------------------------------------------------------------------------
	// fields
	//--------------------------------------------------------------------------------
	protected static $is_singleton = true;
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		// init
		$this->name = "Form";
	}
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function build($options = []) {
		// public static function form($id, $action = false, $options = []) {

		// options
		$options = array_merge([
			"id" => false,
			"action" => false,

			"@method" => "post",
			"@noserialize" => false,
		], $options);

		// init
		$id = $options["id"];
		$action = $options["action"];

		// id
		$options["@id"] = $id;
		$options["@name"] = $id;

		// action
		$options["@action"] = $action;

		// html
		$html = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->tag()->form_(false, $options);

		// csrf token
		$html .= \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->tag()->input(["@type" => "hidden", "@value" => \core::$app->get_response()->get_csrf(), "@id" => \LiquidedgeApp\Octoapp\app\app\str\str::get_random_id("_csrf"), "@name" => "_csrf", "@noserialize" => "noserialize"]);

		// serialize
		if (!$options["@noserialize"]) {
			\LiquidedgeApp\Octoapp\app\app\js\js::add_domready_script("$('#{$id}').data('serialize', $('#{$id}').find('input:not([noserialize], [type=hidden]), select:not([noserialize], [type=hidden]), textarea:not([noserialize], [type=hidden])').serialize());");
		}

  		// done
		return $html;
	}
	//--------------------------------------------------------------------------------
}