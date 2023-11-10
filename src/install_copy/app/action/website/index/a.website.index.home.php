<?php

namespace action\website\index;

/**
 * @package action\website\index
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class home implements \com\router\int\action {

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

	    $buffer = \app\ui::make()->buffer();

        $buffer->section_();
            $buffer->div_([".container py-4" => true, ]);
                $buffer->div_([".row my-3" => true, ]);
                    $buffer->div_([".col border-bottom" => true, ]);
                        $buffer->xheader(1, "HOME");
                    $buffer->_div();
                $buffer->_div();
            $buffer->_div();
        $buffer->_section();

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

