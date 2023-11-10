<?php

namespace action\website\index;

/**
 * @package action\website\index
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class policy implements \com\router\int\action {

	//--------------------------------------------------------------------------------
	use \com\router\tra\action;
	//--------------------------------------------------------------------------------
	// functions
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
	public function run() {

	    $slug = \app\http::get_slug();

	    $solid = \app\solid::get_setting_instance_from_id($slug);

		if(!$solid) \app\http::go_error(404);

        $buffer = \com\ui::make()->buffer();
        $buffer->div_([".container" => true, ]);
            $buffer->div_([".row p-3" => true, ]);
                $buffer->div_([".col-12 mb-4" => true, ]);
                    $buffer->xheader(3, $solid->get_display_name());
                $buffer->_div();
                $buffer->div_([".col-xl-12" => true, ]);
                    $buffer->add(nl2br($solid->get_value()));
                $buffer->_div();
            $buffer->_div();
        $buffer->_div();

        $buffer->flush();
	}
	//--------------------------------------------------------------------------------
}