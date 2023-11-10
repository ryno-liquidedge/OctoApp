<?php

namespace action\index;

/**
 * Action Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class vforgotpassword implements \com\router\int\action {
	//--------------------------------------------------------------------------------
	use \com\router\tra\action;
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		// options
		$options = array_merge([
		], $options);

		\core::$app->set_section(\acc\core\section\application_web::make());
	}
	//--------------------------------------------------------------------------------
	public function auth() {
		return true;
	}
	//--------------------------------------------------------------------------------
	public function run() {
		// params
		$username = $this->request->get("username", \com\data::TYPE_STRING);

		// check if logged in
		if (\com\user::is_loggedin()) return \com\http::go_error(6);

		// check if we have the secret code
		$login_key = \core::$app->get_instance()->get_login_key();
		if ($login_key && $this->request->get_id() != $login_key) {
			return \com\http::redirect(\core::$app->get_instance()->get_login_key_url());
		}

		// init
		$copyright = \core::$app->get_instance()->get_copyright();


		$buffer = \app\ui::make()->html_buffer();
		$buffer->form("?c=index/xforgotpassword");
		$buffer->style(["*" => "
		    a.text-white{color:var(--bs-dark) !important}
		"]);
		$buffer->section_([".bg-white" => true]);
			$buffer->div_([".container-fluid" => true, ]);
				$buffer->div_([".row justify-content-center" => true, ]);
					$buffer->div_([".col-12 col-lg-8 col-xl-6 col-xxl-5 d-flex justify-content-center align-items-center min-h-100vh" => true, ]);

					    $buffer->div_([".row bg-light px-5 pt-5 pb-3 align-items-center" => true]);
					        $buffer->div_([".col-auto" => true]);
					            $buffer->ximage(\app\http::get_stream_url(\db_settings::get_company_logo_light()), ["@alt" => "logo", ".img-fluid max-w-250px" => true]);
					        $buffer->_div();

					        $buffer->div_([".col-12 col-lg" => true]);
					            $buffer->header(4, "Forgot Password", false, [".mb-3" => true]);

								$buffer->div_([".mb-4" => true]);
									$buffer->xform_label("Email", "per_email");
									$buffer->itext(false, "per_email", $username, ["required" => true]);
								$buffer->_div();

								$buffer->div_([".d-flex" => true]);
									$buffer->button("cancel", "document.location = '?c=index/vlogin';", [".w-100 btn-secondary" => true]);
									$buffer->submit_button_js([
										"label" => "Reset Password",
										".w-100" => true,
									]);
								$buffer->_div();

								\com\js::add_script("
									$(function(){
										$('body').on('click', '.btn-submit', function(){
											let el = $(this);
											let form = $('#{$buffer->form->id_form}');
											if({$buffer->form->cid}.validate({enable_error_popup: false,})){
												".\app\js::ajax($buffer->form->action, [
													"*beforeSend" => "function(){ core.overlay.show(); }",
													"*form" => "#{$buffer->form->id_form}",
													"*success" => "function(response){ core.ajax.process_response(response); }",
												])."
											}
										});
									});
								");

					        $buffer->_div();

                            if($copyright){
                                $buffer->div_([".col-12 text-center mt-4" => true]);
                                    $buffer->p(["*" => str_replace("fill: #ffffff;", "fill: var(--bs-dark);", $copyright), ".p-0 text-dark" => true, "#font-size" => "13px"]);
                                $buffer->_div();
                            }
					    $buffer->_div();



					$buffer->_div();
				$buffer->_div();
			$buffer->_div();

        $buffer->_section();
        $buffer->flush();


	}
	//--------------------------------------------------------------------------------
}