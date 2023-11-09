<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\custom;

/**
 * @package app\ui\set\custom
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class share_btn_group extends \LiquidedgeApp\Octoapp\app\app\ui\intf\element {
	//--------------------------------------------------------------------------------
	// fields
	//--------------------------------------------------------------------------------
	public function build($options = []) {
		$buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();
		$buffer->div_([".btn-group" => true, "@role" => "group", ]);

			$buffer->xbutton(false, "ui.social.share.facebook()", [
				"@style" => "background: var(--bs-blue);color: var(--bs-primary-bg-subtle);",
				"icon" => "fab-facebook",
			]);

			$buffer->xbutton(false, "ui.social.share.twitter()", [
				"@style" => "background: var(--bs-info);color: var(--bs-primary-bg-subtle);",
				"icon" => "fab-twitter",
			]);

			$buffer->xbutton(false, "ui.social.share.pinterest()", [
				"@style" => "background: var(--bs-form-invalid-border-color);color: var(--bs-primary-bg-subtle);",
				"icon" => "fab-pinterest",
			]);

			$buffer->xbutton(false, "ui.social.share.linkedin()", [
				"@style" => "background: var(--bs-purple);color: var(--bs-primary-bg-subtle);",
				"icon" => "fab-linkedin-in",
			]);

			$buffer->xbutton(false, "ui.social.share.mailto()", [
				"@style" => "background: var(--bs-yellow);color: var(--bs-primary-bg-subtle);",
				"icon" => "fa-envelope",
			]);

			$buffer->xbutton(false, "ui.social.share.whatsapp()", [
				"@style" => "background: var(--bs-teal);color: var(--bs-primary-bg-subtle);",
				"icon" => "fab-whatsapp",
			]);

		$buffer->_div();
		return $buffer->build();
	}
	//--------------------------------------------------------------------------------
}