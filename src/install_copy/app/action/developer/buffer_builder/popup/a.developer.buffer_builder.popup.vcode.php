<?php

namespace action\developer\buffer_builder\popup;

/**
 * Class vedit
 * @package action\system\setup\acl_role
 * @author Ryno Van Zyl
 */
class vcode implements \com\router\int\action {
	//--------------------------------------------------------------------------------
	use \com\router\tra\action;

	//--------------------------------------------------------------------------------
	public function auth() {
		return \core::$app->get_token()->check("dev");
	}

	//--------------------------------------------------------------------------------
	public function run() {

		$html = $this->session->get("buffer_builder_html");

		$builder = \app\helper\buffer\builder::make();
	    $builder->add_html($html);

	    echo \app\ui::make()->itextarea("html", $builder->build(["wrap" => false]), false, 30);

	}
	//--------------------------------------------------------------------------------
}

