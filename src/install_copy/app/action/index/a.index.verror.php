<?php

namespace action\index;

/**
 * Action Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class verror implements \com\router\int\action {
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		// options
		$options = array_merge([
		], $options);

		\core::$app->set_section(\acc\core\section\application_web::make());
	}
	//--------------------------------------------------------------------------------
	use \com\router\tra\action;
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	public function auth() {
		return true;
	}
	//--------------------------------------------------------------------------------
	public function run() {
		// params
		$error = $this->request->get_id();
		$copyright = \core::$app->get_instance()->get_copyright();

		// message
	    $error_data = \app\http::get_error($error);

	    $title = $error_data["title"];
	    $sub_title = isset($error_data["sub_title"]) ? $error_data["sub_title"] : false;
	    $message = $error_data["message"];

        $buffer = \com\ui::make()->buffer();

        $buffer->section_([".highlight-clean min-h-100vh d-flex align-items-center bg-white" => true]);
            $buffer->div_([".container" => true]);
            	$buffer->div_([".row justify-content-center" => true]);
            	    $buffer->div_([".col-12 col-lg-8 col-xl-6 py-5 message-wrapper" => true]);
						$buffer->div_([".row justify-content-center my-4" => true]);
							$buffer->div_([".col-12" => true]);
								$buffer->div_([".intro text-center" => true]);
									$buffer->xheader(2, $title, false, [".mb-2" => true]);
									if($sub_title) $buffer->xheader(4, $sub_title, false, [".text-muted mb-4" => true]);
									$buffer->p(["*" => $message]);
								$buffer->_div();
							$buffer->_div();

							$buffer->div_([".col-12 col-lg-8 mt-3" => true]);
								$buffer->div_([".d-flex justify-content-center" => true]);
									$add_button = function($label, $link, $options = []) use(&$buffer){
										$options = array_merge([
											"*" => ucwords($label),
											"@href" => $link,
											"color" => "btn-primary",
											".btn btn-primary w-100 me-1 com-html-width-large" => true,
										], $options);

										$options[".{$options["color"]}"] = true;

										$buffer->a($options);
									};

									switch ($error) {
										case 1 	:
										case 2 	:
										case 9 	:
											$add_button("Try Again", \app\http::get_seo_url("ui_login"));
											break;

										case 6 	:
											$add_button("Continue Session", "?c=index/vhome");
											$add_button("Logout", "?c=index/xlogout", ["color" => "btn-secondary"]);
											break;

										case 10 :
										case 11 :
										case 15 :
										case 16 :
										case 404 :
											$add_button("Go Home", "?c=index/vhome");
											break;

										case 12 :
											$add_button("Cancel",  \app\http::get_seo_url("ui_login"));
											$add_button("Try Again", "?c=index/vforgotpassword", ["color" => "btn-secondary"]);
											break;
										case 100 :
											$add_button("Manage my Profile",  false, ["!click" => "document.location='".\app\http::get_seo_url("ui_client_profile")."'"]);
											$add_button("Go Home", "?c=index/vhome");
											break;

										default :
											if(\com\user::$active) $add_button("go to home", "?c=index/vhome");
											else $add_button("login",  \app\http::get_seo_url("ui_login"));
											break;
									}

								$buffer->_div();
							$buffer->_div();

						$buffer->_div();
            	    $buffer->_div();
            	$buffer->_div();
            $buffer->_div();
        $buffer->_section();

        $buffer->flush();

	}
	//--------------------------------------------------------------------------------
}