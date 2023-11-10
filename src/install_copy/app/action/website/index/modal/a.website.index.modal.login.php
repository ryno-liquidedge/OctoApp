<?php

namespace action\website\index\modal;

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

	    $buffer = \app\ui::make()->html_buffer();

        $buffer->form("?c=website.index.functions/xlogin");
        $buffer->ihidden("panel", $this->request->get_panel());

        $buffer->div_([".container" => true, ]);
            $buffer->div_([".row justify-content-center align-items-center" => true, ]);
                $buffer->div_([".col-sm-9 col-md-10 text-center" => true, ]);
                    $buffer->div_([".row align-items-center justify-content-center py-2" => true]);
                        $buffer->div_([".col-12" => true]);

                            $buffer->div_([".row justify-content-center mb-2" => true, ]);
                                $buffer->div_([".col-8 col-md-7" => true, ]);
                                    $buffer->xheader(1, "Login");
                                $buffer->_div();
                            $buffer->_div();
                            $buffer->div_([".row justify-content-center" => true, ]);
                                $buffer->div_([".col-12" => true, ]);
                                    $buffer->p(["*" => "Enter the details that you signed up with below."]);
                                $buffer->_div();
                            $buffer->_div();

                            $buffer->div_([".row justify-content-start align-items-center mb-4 text-start" => true, ]);
                                $buffer->div_([".col-12" => true, ]);
                                    $buffer->label(["*" => "Username", "@for" => "per_username", ".m-0" => true]);
                                $buffer->_div();
                                $buffer->div_([".col-12" => true, ]);
                                    $buffer->itext(false, "per_username", false, [
                                        "focus" => true,
                                        "required" => true,
                                        "!enter" => "$('.btn-login-form-submit').click();",
                                        "required_icon" => false,
                                        ".form-control-lg" => true,
                                        "@placeholder" => "Username",
                                    ]);
                                $buffer->_div();
                            $buffer->_div();

                            $buffer->div_([".row justify-content-start align-items-center mb-4 text-start" => true, ]);
                                $buffer->div_([".col-12" => true, ]);
                                    $buffer->label(["*" => "Password", "@for" => "per_password", ".m-0" => true]);
                                $buffer->_div();
                                $buffer->div_([".col-12" => true, ]);
                                    $buffer->itext(false, "per_password", false, [
                                        "required" => true,
                                        "mask" => true,
                                        "!enter" => "$('.btn-login-form-submit').click();",
                                        "required_icon" => false,
                                        ".form-control-lg" => true,
                                        "@placeholder" => "Password",
                                    ]);
                                $buffer->_div();
                            $buffer->_div();

                            $buffer->div_([".row justify-content-center mb-4" => true, ]);
                                $buffer->div_([".col-12 d-flex" => true, ]);
                                    $buffer->xbutton("Cancel", "core.browser.close_popup()", [".btn btn-lg w-100 me-2" => true, ".btn-secondary" => true, "icon" => "fa-times"]);
                                    $buffer->submit_button_js(["label" => "Login", ".btn-primary w-100 btn-login-form-submit" => true, ".me-2" => false, "enable_error_feedback" => false, "enable_error_popup" => false]);
                                $buffer->_div();
                            $buffer->_div();

                            $buffer->div_([".row justify-content-center mb-2" => true, ]);
                                $buffer->div_([".col-10 fs-6" => true, ]);
                                    $buffer->xicon("fa-user-plus", [".text-secondary" => true]);
                                    $buffer->xlink("javascript:".\core::$app->get_request()->get_panel().".modal_switch('?c=website.index.modal/register', {width:'modal-md'})", "Register here.");
                                $buffer->_div();
                            $buffer->_div();

                            $buffer->div_([".row justify-content-center mb-4" => true, ]);
                                $buffer->div_([".col-10 fs-6" => true, ]);
                                    $buffer->xicon("fa-question-circle", [".text-secondary" => true]);
                                    $buffer->xlink("javascript:{$this->request->get_panel()}.requestUpdate('?c=website.index.modal/forgot_password')", "Forgot your password?");
                                $buffer->_div();
                            $buffer->_div();

                        $buffer->_div();
                    $buffer->_div();
                $buffer->_div();
            $buffer->_div();
        $buffer->_div();

	    $buffer->flush();

    }
    //--------------------------------------------------------------------------------
}

