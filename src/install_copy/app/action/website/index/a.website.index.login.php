<?php

namespace action\website\index;

/**
 * @package action\website\index
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class login implements \com\router\int\action {

    //--------------------------------------------------------------------------------
	use \com\router\tra\action;
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		// options
		$options = array_merge([
		], $options);

		\core::$app->set_section(\acc\core\section\website_no_audit::make());
	}
    //--------------------------------------------------------------------------------
    public function auth() { 
        return true;
    }
    //--------------------------------------------------------------------------------
    public function run () {

	    if(\com\user::$active)
	        return \app\http::redirect(\app\http::get_seo_url("ui_error", 6));

	    $buffer = \app\ui::make()->buffer();
        $buffer->div_([".bg-dark p-3 p-sm-5" => true, "@style" => "background: url(".\app\http::get_stream_url(\core::$folders->get_root_files()."/img/pattern-background-home.png").") bottom right / auto no-repeat;", ]);
            $buffer->div_([".container" => true, ]);
                $buffer->div_([".row gx-1 gy-1 align-items-stretch justify-content-center mt-sm-3 mb-4" => true, ]);
                    $buffer->div_([".col-12 col-md-6" => true, ]);
                        $buffer->xfieldset(false, function($html){
                            $buffer = \app\ui::make()->html_buffer([
                                ".bg-white rounded-3 py-xl-2 px-xl-4 h-100" => true
                            ]);
                            $buffer->form("?c=website.index.functions/xlogin");
                            $buffer->xheader(3, "Log into your account", false, [".text-start text-md-start mb-4 mt-3" => true, ]);
                            $buffer->xitext("per_username", false, "Email Address *", ["required" => true, "required_icon" => false, "label_col" => 4]);
                            $buffer->xitext("per_password", false, "Password *", ["required" => true, "required_icon" => false, "label_col" => 4, "mask" => true, "append" => \app\ui::make()->iconbutton(false, false, "fa-eye", ["label_hidden" => true, ".me-2" => false, ".toggle-field-mask" => true, "@data-target" => "#per_password"])]);
                            $buffer->div_([".row mt-3" => true]);
                                $buffer->div_([".col-12 text-center" => true]);
                                    $buffer->p_([".m-0" => true]);
                                        $buffer->add("Forgot your password? ");
                                        $buffer->xlink("javascript:".\app\ui::make()->js_popup("?c=website.index.modal/forgot_password", [
                                            "*width" => "modal-md",
                                            "*height_class" => "min-h-400px",
                                            "*hide_header" => true,
                                        ]), "Reset it here.");
                                    $buffer->_p();
                                $buffer->_div();
                            $buffer->_div();
                            $buffer->submit_button_js([
                                "label" => "Login",
                                "icon" => false,
                                ".w-100 btn-primary mt-3 fs-5" => true,
                                "enable_error_popup" => false,
                                "enable_error_feedback" => false,
                            ]);

                            $buffer->div_([".row mt-3" => true]);
                                $buffer->div_([".col-12 text-center" => true]);
                                    $buffer->p_();
                                        $buffer->add("Dont have an account? ");
                                        $buffer->xlink(\app\http::get_seo_url("ui_register"), "Register here.", [".text-secondary" => true]);
                                    $buffer->_p();
                                $buffer->_div();
                            $buffer->_div();

                            $html->add($buffer->build());

                        }, ["no_border" => true, ".bg-white rounded-3 p-3 p-sm-4 p-lg-5 h-100 m-0" => true]);
                    $buffer->_div();
                $buffer->_div();
            $buffer->_div();
        $buffer->_div();

	    $buffer->flush();

    }
    //--------------------------------------------------------------------------------
	public static function get_page_meta() {

		$page_meta = \app\ui\page_meta::make();
		$page_meta->load();

		return $page_meta->get_meta_arr();
	}
    //--------------------------------------------------------------------------------
}

