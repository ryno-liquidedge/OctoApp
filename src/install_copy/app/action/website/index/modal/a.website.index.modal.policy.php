<?php

namespace action\website\index\modal;

/**
 * @package action\website\index
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class policy implements \com\router\int\action {

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

	    $stt_key = $this->request->get('stt_key', \com\data::TYPE_STRING, ["get" => true]);
	    $solid = \app\solid::get_setting_instance(constant(strtoupper($stt_key)));

	    $buffer = \app\ui::make()->html_buffer();

	    $buffer->div_([".container" => true, ]);
            $buffer->div_([".row mt-2 justify-content-end" => true]);
                $buffer->div_([".col-12 text-end" => true]);
                    $buffer->xbutton(false, false, ["@data-bs-dismiss" => "modal", "@aria-label" => "Close", "@class" => "btn btn-outline-light border-0 fs-4", "icon" => "fa-times"]);
                $buffer->_div();
            $buffer->_div();

            $buffer->div_([".row mt-2 justify-content-center" => true]);
                $buffer->div_([".col-12 col-md-8" => true]);
                    $buffer->xheader(1, $solid->get_display_name(), false, [".text-white" => true]);
                    $buffer->hr([".bg-white" => true]);
                    $buffer->p(["*" => nl2br($solid->get_value()), ".text-light" => true]);
                $buffer->_div();
            $buffer->_div();
        $buffer->_div();

	    $buffer->flush();

    }
    //--------------------------------------------------------------------------------
}

