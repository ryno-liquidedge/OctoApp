<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\custom;

/**
 * @package app\ui\set\bootstrap
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class mailchimp_signup extends \com\ui\intf\element {
	//--------------------------------------------------------------------------------
	// methods
	//--------------------------------------------------------------------------------
	public function build($options = []) {

		$options = array_merge([
		    "action" => false,
		    "honeypot" => false,
		], $options);


		$buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->html_buffer();
		$buffer->form("");
		$buffer->xitext("mc_email", false, "Email Address", ["required" => true, "floating_label" => true, "limit" => "email"]);
		$buffer->xitext("mc_firstname", false, "Firstname", ["required" => true, "floating_label" => true]);
		$buffer->xitext("mc_lastname", false, "Surname", ["required" => true, "floating_label" => true]);

		return $buffer->build();

	}
  	//--------------------------------------------------------------------------------
}