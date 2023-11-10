<?php

namespace action\website\index\modal;

/**
 * @package action\website\profile
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class reset_password implements \com\router\int\action {

	/**
	 * @var \db_person
	 */
    protected $person;

    //--------------------------------------------------------------------------------
	use \com\router\tra\action;
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		// options
		$options = array_merge([
		], $options);

		\core::$app->set_section(\acc\core\section\website::make(["layout" => "web_clean"]));
	}
    //--------------------------------------------------------------------------------
    public function auth() { 
        return true;
    }
    //--------------------------------------------------------------------------------
    public function run () {

		$request = $this->session->get("change_password_request");
	    $this->person = \core::dbt("person")->splat($request->req_ref_reference);
		if(!$request || !$this->person) return \app\http::go_error_frontend(7);

	    $buffer = \app\ui::make()->html_buffer();
	    $buffer->form("?c=website.index.functions/xreset_password");

	    $buffer->div_([".container" => true, ]);
            $buffer->div_([".row justify-content-center align-items-center" => true, ]);
                $buffer->div_([".col-sm-9 col-md-8 col-lg-5 col-xl-5 col-xxl-4 offset-lg-0 text-center" => true, ]);
                    $buffer->div_([".row min-vh-100 align-items-center justify-content-center py-5" => true]);
                        $buffer->div_([".col-12" => true]);

                            $buffer->div_([".row justify-content-center mb-2" => true, ]);
                                $buffer->div_([".col-12" => true, ]);
                                    $buffer->xheader(1, "Reset Password", false);
                                $buffer->_div();
                            $buffer->_div();
                            $buffer->div_([".row justify-content-center mb-2" => true, ]);
                                $buffer->div_([".col-10" => true, ]);
                                    $buffer->p(["*" => "Enter your new password and confirm new password below."]);
                                $buffer->_div();
                            $buffer->_div();


                            $buffer->div_([".row justify-content-start align-items-center mb-4 text-start" => true, ]);
                                $buffer->div_([".col-12" => true, ]);
                                    $buffer->label(["*" => "New Password", "@for" => "per_password_new", ".m-0" => true]);
                                $buffer->_div();
                                $buffer->div_([".col-12" => true, ]);
                                    $buffer->itext(false, "per_password_new", false, [
                                        "focus" => true,
                                        "required_icon" => false,
                                        "mask" => true,
                                    ]);
                                $buffer->_div();
                            $buffer->_div();

                            $buffer->div_([".row justify-content-start align-items-center mb-4 text-start" => true, ]);
                                $buffer->div_([".col-12" => true, ]);
                                    $buffer->label(["*" => "Confirm Password", "@for" => "per_password_confirm", ".m-0" => true]);
                                $buffer->_div();
                                $buffer->div_([".col-12" => true, ]);
                                    $buffer->itext(false, "per_password_confirm", false, [
                                        "required_icon" => false,
                                        "mask" => true,
                                    ]);
                                $buffer->_div();
                            $buffer->_div();

                            $buffer->form->add_validation_empty("per_password_new", "New Password");
                            $buffer->form->add_validation_empty("per_password_confirm", "Confirm Password");
                            $buffer->form->add_validation_notequal("per_password_new", "New Password", "per_password_confirm", "Confirm Password");

                            $buffer->div_([".row justify-content-center mb-4" => true, ]);
                                $buffer->div_([".col-10 d-flex" => true, ]);
                                    $buffer->xbutton("Cancel", "core.browser.close_popup()", [".btn btn-lg w-100 me-2" => true, ".btn-secondary" => true, "icon" => "fa-times"]);
                                    $buffer->submit_button_js(["label" => "Submit", ".btn-primary w-100" => true, ".me-2" => false]);
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

