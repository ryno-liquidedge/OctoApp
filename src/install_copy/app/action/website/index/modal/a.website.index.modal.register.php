<?php

namespace action\website\index\modal;

/**
 * @package action\website\index
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class register implements \com\router\int\action {

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

        $buffer->form("?c=website.index.functions/xregister");
        $buffer->ihidden("panel", $this->request->get_panel());

        $buffer->div_([".container" => true, ]);
            $buffer->div_([".row justify-content-center align-items-center" => true, ]);
                $buffer->div_([".col-sm-9 col-md-10 text-center" => true, ]);
                    $buffer->div_([".row align-items-center justify-content-center py-2" => true]);
                        $buffer->div_([".col-12" => true]);

                            $buffer->div_([".row justify-content-center mb-2" => true, ]);
                                $buffer->div_([".col-8 col-md-7" => true, ]);
                                    $buffer->xheader(1, "Register", false);
                                $buffer->_div();
                            $buffer->_div();
                            $buffer->div_([".row justify-content-center" => true, ]);
                                $buffer->div_([".col-12" => true, ]);
                                    $buffer->p(["*" => "Please complete the form below to setup your account."]);
                                $buffer->_div();
                            $buffer->_div();

                            $buffer->xitext("per_firstname", false, "Name", [
                                "focus" => true,
                                "required" => true,
                                "required_icon" => false,
                                ".form-control-lg" => true,
                                "label_col" => 12,
                                "/wrapper" => [".align-items-center" => true],
                            ]);

                            $buffer->xitext("per_lastname", false, "Surname", [
                                "focus" => true,
                                "required" => true,
                                "required_icon" => false,
                                ".form-control-lg" => true,
                                "label_col" => 12,
                                "/wrapper" => [".align-items-center" => true],
                            ]);

                            $buffer->xitext("per_cellnr", false, "Contact Nr", [
                                "required" => true,
                                "required_icon" => false,
                                ".form-control-lg" => true,
                                "label_col" => 12,
                                "/wrapper" => [".align-items-center" => true],
                            ]);

                            $buffer->xitext("per_email", false, "Email Address", [
                                "required" => true,
                                "required_icon" => false,
                                ".form-control-lg" => true,
                                "label_col" => 12,
                                "limit" => "email",
                                "/wrapper" => [".align-items-center" => true],
                            ]);

                            $buffer->xitext("per_password_register", false, "Password", [
                                "required" => true,
                                "required_icon" => false,
                                ".form-control-lg" => true,
                                "label_col" => 12,
                                "limit" => "email",
                                "/wrapper" => [".align-items-center" => true],
                            ]);

                            $buffer->xitext("per_password_register_confirm", false, "Confirm Password", [
                                "required" => true,
                                "required_icon" => false,
                                ".form-control-lg" => true,
                                "label_col" => 12,
                                "limit" => "email",
                                "/wrapper" => [".align-items-center" => true],
                            ]);

                            $buffer->xiswitch("per_terms_accepted", false, false, "Terms & Conditions", ["label_col" => 6, "required_icon" => false, "required" => true, ".my-3" => true]);

                            $buffer->div_([".row mb-4" => true, ]);
                                $buffer->div_([".col-12 d-flex" => true, ]);
                                    $buffer->xbutton("Cancel", "core.browser.close_popup()", [".btn btn-lg w-100 me-2" => true, ".btn-secondary" => true, "icon" => "fa-times"]);
                                    $buffer->submit_button_js(["label" => "Submit", ".btn-primary w-100 btn-login-form-submit" => true, ".me-2" => false, "enable_error_feedback" => false, "enable_error_popup" => false]);
                                $buffer->_div();
                            $buffer->_div();

                            $buffer->div_([".row justify-content-center mb-2" => true, ]);
                                $buffer->div_([".col-10 fs-6" => true, ]);
                                    $buffer->xicon("fa-question-circle", [".text-secondary" => true]);
                                    $buffer->xlink("javascript:".\core::$app->get_request()->get_panel().".modal_switch('?c=website.index.modal/login', {width:'modal-md'})", "Already have an account? Login here.");
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

