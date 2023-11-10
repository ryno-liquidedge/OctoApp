<?php

namespace action\developer\buffer_builder;

/**
 * Class vedit
 * @package action\system\setup\acl_role
 * @author Ryno Van Zyl
 */
class vedit implements \com\router\int\action {
	//--------------------------------------------------------------------------------
	use \com\router\tra\action;

	//--------------------------------------------------------------------------------
	public function auth() {
		return \core::$app->get_token()->check("dev");
	}

	//--------------------------------------------------------------------------------
	public function run() {

		$html = $this->session->get("buffer_builder_html");

		// html
		$buffer = \app\ui::make()->html_buffer();
		$buffer->header(3, "Buffer Builder");
		$buffer->form("?c=developer.buffer_builder.functions/xedit");
		$buffer->button("Parse HTML", \app\js::ajax($buffer->form->action, [
			"*complete" => "{$this->request->get_panel()}.popup('?c=developer.buffer_builder.popup/vcode', {width:'modal-fullscreen'})",
			"form" => $buffer->form,
		]));

		$buffer->xfieldset("HTML", function($buffer)use($html){
			$buffer->xitextarea("html", $html, false, 20);
		});

		$buffer->flush();

	}
	//--------------------------------------------------------------------------------
}

