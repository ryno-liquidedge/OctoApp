<?php

namespace action\website\index\modal;

/**
 * @package action\website\index
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class forgot_password implements \com\router\int\action {

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

	    $per_username = $this->request->get('per_username', \com\data::TYPE_STRING);

	    $buffer = \app\ui::make()->html_buffer();

        $buffer->form("?c=website.index.functions/xforgot_password");

        $buffer->div_([".container" => true, ]);
            $buffer->div_([".row align-items-center justify-content-center py-4" => true]);
                $buffer->div_([".col-12 col-md-10" => true]);

                    $buffer->div_([".row justify-content-center mb-2" => true, ]);
                        $buffer->div_([".col-12 text-center" => true, ]);
                            $buffer->xheader(1, "Forgot Password", false, [".text-dark" => true]);
                        $buffer->_div();
                    $buffer->_div();
                    $buffer->div_([".row justify-content-center mb-2" => true, ]);
                        $buffer->div_([".col-12  text-center" => true, ]);
                            $buffer->p(["*" => "Enter the details that you signed up with below.", ".text-dark" => true]);
                        $buffer->_div();
                    $buffer->_div();

                    $buffer->div_([".row justify-content-start align-items-center mb-4 text-start" => true, ]);
                        $buffer->div_([".col-12" => true, ]);
                            $buffer->label(["*" => "Email", "@for" => "per_email", ".text-dark m-0" => true]);
                        $buffer->_div();
                        $buffer->div_([".col-12" => true, ]);
                            $buffer->itext(false, "registered_email", $per_username, [
                                "focus" => true,
                                "limit" => \core::$app->get_instance()->get_environment() == "DEV" ? false : "email",
                                "required_icon" => false,
                                ".form-control-lg" => true,
                                "@placeholder" => "user@mail.com",
                            ]);
                        $buffer->_div();
                    $buffer->_div();

                    $buffer->div_([".row justify-content-center mb-4" => true, ]);
                        $buffer->div_([".col-12 d-flex" => true, ]);
                            $buffer->xbutton("Cancel", "core.browser.close_popup()", [".btn btn-lg w-100 me-2" => true, ".btn-secondary" => true, "icon" => "fa-times"]);
                            $buffer->submit_button_js(["label" => "Submit", ".btn-primary w-100 btn-login-form-submit" => true, ".me-2" => false]);
                        $buffer->_div();
                    $buffer->_div();

                $buffer->_div();
            $buffer->_div();
        $buffer->_div();

	    $buffer->flush();

    }
    //--------------------------------------------------------------------------------
}

