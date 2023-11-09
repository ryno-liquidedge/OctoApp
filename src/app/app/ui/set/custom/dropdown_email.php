<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\custom;

/**
 * @package app\ui\set\custom
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class dropdown_email extends \LiquidedgeApp\Octoapp\app\app\ui\intf\element {
	//--------------------------------------------------------------------------------
	// fields
	//--------------------------------------------------------------------------------
	protected $item_arr = [];
	//--------------------------------------------------------------------------------
	public function build($options = []) {

		$options = array_merge([
		    "title" => false,
		    "email" => false,
		    "icon" => false,
		    "!send_email" => false,
		    "data" => [],
		], $options);

		$email = $options["email"];
		$title = $options["title"];
		if(!$title) $title = $email;

		if(!$email) return "n/a";

		$options["data"]["email"] = $email;
		if(!$options["!send_email"]) $options["!send_email"] = \LiquidedgeApp\Octoapp\app\app\js\js::ajax("?c=system.email.functions/xstart", [
			"*data" => $options["data"],
			"*success" => "function(){
				core.browser.popup('?c=system.email/vcompose', {width:'modal-md',title:'Compose Email'});
			}",
		]);

    	$dropdown_email = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->dropdown();
		$dropdown_email->add_link("Copy Email", "javascript:core.util.copy_text_to_clipboard('$email')", ["icon" => "fa-clipboard"]);
		$dropdown_email->add_link("Send Email", "mailto:{$email}", ["icon" => "fa-envelope"]);
		return \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->link($dropdown_email, $title, $options);

	}
	//--------------------------------------------------------------------------------
}