<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class ilabel extends \com\ui\intf\element {
	//--------------------------------------------------------------------------------
	// fields
	//--------------------------------------------------------------------------------
	protected static $is_singleton = true;
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		// init
		$this->name = "Input Label";
	}
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function build($options = []) {
		// options
		$options = array_merge([
		    "id" => false,
		    "value" => false,
		    "!click" => false,
		    "wrapper_id" => \LiquidedgeApp\Octoapp\app\app\str\str::get_random_id("ilabel"),
		], $options);

		if(isnull($options["value"])) $options["value"] = false;

		$buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();
		$buffer->div_([".edit-label-wrapper w-100" => true, "@id" => $options["wrapper_id"]]);
            $buffer->div_([".label-wrapper" => true]);
                $buffer->span(["*" => $options["value"], ".label" => true]);
            $buffer->_div();
            $buffer->div_([".input-wrapper d-none" => true]);
                $buffer->xitext($options["id"], $options["value"], false, [
                    ".ui-itext form-control" => true,
                    "append" => \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->button(false, $options["!click"], ["icon" => "fa-save", ".me-1 btn-sm btn-outline-success btn-save" => true]). \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->button(false, false, ["icon" => "fa-times", ".btn-outline-danger btn-sm btn-cancel" => true]),
                    "append_type" => "button",
                    "!enter" => "
                        let wrapper = $('#{$options["wrapper_id"]}');
                        
                        wrapper.find('.btn-save').click();
                        wrapper.find(' .input-wrapper').addClass('d-none');
                        wrapper.find(' .label-wrapper').removeClass('d-none').html($('#{$options["id"]}').val());
                        
                        $('#{$options["wrapper_id"]}.edit-label-wrapper .label-wrapper').html();
                    ",
                    "/wrapper" => [".mb-2" => false],
                ]);
            $buffer->_div();
        $buffer->_div();

        $buffer->script(["*" => "
            $(function(){
                $('body').on('click', '#{$options["wrapper_id"]}.edit-label-wrapper', function(){
                    let el = $(this);
                    let el_label_wrapper = el.find('.label-wrapper');
                    let el_input_wrapper = el.find('.input-wrapper');
                    
                    if(el_input_wrapper.is(':visible')){
                        el_input_wrapper.addClass('d-none');
                        el_label_wrapper.removeClass('d-none');
                    }else{  
                        el_input_wrapper.removeClass('d-none');
                        el_label_wrapper.addClass('d-none');
                        $('#{$options["id"]}').focus().select();
                    }
                });
            });
        "]);

        return $buffer->build();

	}
	//--------------------------------------------------------------------------------
}