<?php

namespace action\website\index;

/**
 * Class vmanage
 * @package action\system\setup
 * @author Ryno Van Zyl
 */

class message implements \com\router\int\action {

    //--------------------------------------------------------------------------------
	use \com\router\tra\action;
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		// options
		$options = array_merge([
		], $options);

		\core::$app->set_section(\acc\core\section\website_no_audit::make(["layout" => "web"]));
	}
    //--------------------------------------------------------------------------------
    public function auth() { 
        return true;
    }
    //--------------------------------------------------------------------------------
    public function run () {

		$error = \app\http::get_slug();
	    if(!$error) $error = $this->request->get_id();
	    $error_data = \app\http::get_error($error);

	    $title = $error_data["title"];
	    $sub_title = isset($error_data["sub_title"]) ? $error_data["sub_title"] : false;
	    $message_arr = \com\arr::splat($error_data["message"]);

        $buffer = \com\ui::make()->buffer();

        $buffer->div_([".p-3 p-sm-5" => true, ]);
            $buffer->div_([".container my-7" => true, ]);
                $buffer->div_([".row gx-1 gy-1 align-items-center justify-content-center mt-sm-3 mb-4 min-h-400px" => true, ]);
                    $buffer->div_([".col-12 col-md-6" => true, ]);
                        $buffer->xfieldset(false, function($buffer)use($title, $error, $sub_title, $message_arr){
                            $buffer->div_([".container align-self-center" => true]);
                                $buffer->div_([".row justify-content-center" => true]);
                                    $buffer->div_([".col-12 col-md-10" => true]);
                                        $buffer->div_([".intro text-center" => true]);
                                            $buffer->xheader(2, $title, false, [".mb-2" => true]);
                                            if($sub_title) $buffer->xheader(4, $sub_title, false, [".text-muted mb-4" => true]);
                                            foreach ($message_arr as $message)$buffer->p(["*" => $message]);
                                        $buffer->_div();
                                    $buffer->_div();

                                    $buffer->div_([".col-12 col-md-6 mt-2" => true]);
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
                                                        $add_button("Continue Session", \app\http::get_seo_url("ui_home"));
                                                        $add_button("Logout", \app\http::get_seo_url("ui_logout"), ["color" => "btn-secondary"]);
                                                        $add_button("Logout", \app\http::get_seo_url("ui_logout"), ["color" => "btn-secondary"]);
                                                        break;

                                                    case 10 :
                                                    case 11 :
                                                    case 15 :
                                                    case 16 :
                                                    case 404 :
                                                        $add_button("Go Home", \app\http::get_seo_url("ui_home"));
                                                        break;
                                                    case 100:
                                                    case 3 	:
                                                        $add_button("Login", \app\http::get_seo_url("ui_login"));
                                                        break;

                                                    case 12 :
                                                        $add_button("Cancel",  \app\http::get_seo_url("ui_login"));
                                                        $add_button("Try Again", "/index.php?c=ui.index/forgotpassword", ["color" => "btn-secondary"]);
                                                        break;

                                                    default :
                                                        if(\com\user::$active) $add_button("Go Home", \app\http::get_seo_url("ui_home"));
                                                        else $add_button("Go Home", \app\http::get_seo_url("ui_home"));
                                                        break;
                                                }

                                        $buffer->_div();
                                    $buffer->_div();

                                $buffer->_div();
                            $buffer->_div();

                        }, ["no_border" => true, ".bg-white rounded-3 p-3 p-sm-4 p-lg-5 h-100 m-0" => true]);
                    $buffer->_div();
                $buffer->_div();
            $buffer->_div();
        $buffer->_div();

        $buffer->flush();

    }
    //--------------------------------------------------------------------------------
}

