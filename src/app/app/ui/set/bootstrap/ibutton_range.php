<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class ibutton_range extends \com\ui\intf\element {
	//--------------------------------------------------------------------------------
	// fields
	//--------------------------------------------------------------------------------
	protected $value_option_arr = [];
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		// init
		$this->name = "Input Button Range";
	}
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function add_item($id, $label, $checked = false, $options = []) {
		$this->value_option_arr[]  = array_merge([
			"id" => $id,
			"label" => $label,
			"@checked" => $checked,
			"@disabled" => false,
			"/check-label" => [],
		], $options);
	}
	//--------------------------------------------------------------------------------
	public function build($options = []) {

		// options
		$options = array_merge([
			"name" => false,
			"wrapper_id" => \LiquidedgeApp\Octoapp\app\app\str\str::get_random_id("checkboxes"),
			"!first_click" => "function(){}",
			"!last_click" => "function(){}",
			"!clear" => "function(){}",
			"value" => [],
			"/wrapper" => [],
			"/check-label" => [],
		], $options);

		$name = $options["name"];
		$wrapper_id = $options["wrapper_id"];

		$buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();

		$options["/wrapper"]["@id"] = $wrapper_id;
		$options["/wrapper"][".d-flex flex-wrap range-check-wrapper"] = true;

		$buffer->div_($options["/wrapper"]);
			$fn_input = function($item)use(&$buffer, $name, $wrapper_id, $options){

				$id = "{$name}[{$item['id']}]";
				$__id = \LiquidedgeApp\Octoapp\app\app\str\str::get_random_id("checkbox");

				$options = array_merge([
				    "@type" => "checkbox",
					"@id" => $__id,
					"@nosubmit" => true,
					".btn-check range-check" => true,
					"@data-target" => ".$__id"
				], $item, $options);

				$buffer->xihidden($id, $item["@checked"] ? $id : false, [".$__id hidden-range-check" => true, "@data-value" => $item['id']]);
				$buffer->input($options);

				$item["/check-label"] = array_merge([
					"*" => $item['label'],
					".btn  btn-range-check me-1 mt-1" => true,
					".btn-outline-primary" => !$item["@disabled"],
					".btn-light cursor-not-allowed" => $item["@disabled"],
					"@for" => $__id,
					"@data-target" => ".$__id",
				], $options["/check-label"]);

				$buffer->label($item["/check-label"]);

			};

			foreach ($this->value_option_arr as $item){
				$fn_input($item);
			}

			$buffer->xbutton(false, "$name.clear()", [".btn btn-sm btn-danger btn-clear mt-1 fs-7" => true, ".d-none" => true, "icon" => "fa-ban"]);

		$buffer->_div();



		$buffer->script(["*" => "
		    var $name;
            $(function(){ 
            
                $name = $('#{$wrapper_id}').checkboxes('range_force', true); 
                $name.firstClick = false;
                $name.lastClick = false;
                $name.clear = function(){
                    
                    $name.firstClick = false;
                    $name.lastClick = false;
                    
                    $('#{$wrapper_id}').checkboxes('uncheck'); 
                    $('#{$wrapper_id} .btn-clear').addClass('d-none');
                            
                    let fn = {$options["!clear"]};
                    fn.apply(this);
                };
            
                $('body').on('click', '#{$wrapper_id} .btn-range-check', function(){
                    
                    if($name.firstClick && $name.lastClick){
                        $name.clear();
                    }
                    
                    if(!$name.firstClick){
                        $name.firstClick = true;
                        let fn = {$options["!first_click"]};
                        fn.apply(this);
                    }
                    else if($name.firstClick && !$name.lastClick){
                        $name.lastClick = true;
                        let fn = {$options["!last_click"]};
                        fn.apply(this);
                    }
                    
                    $('#{$wrapper_id} .hidden-range-check').each(function(){
                        $(this).val('');
                    });
                    setTimeout(function(){
                    
                        if(!$('#{$wrapper_id} .range-check:checked').length){
                            $name.clear();
                        }
                    
                        $('#{$wrapper_id} .range-check:checked').each(function(){
                            let target = $($(this).data('target'));
                            target.val(target.data('value'));
                        });
                        
                        $('#{$wrapper_id} .btn-clear').toggleClass('d-none', !$('#{$wrapper_id} .range-check:checked').length);
                        
                    }, 100);
                }); 
            }); 
		"]);

		return $buffer->build();

	}
	//--------------------------------------------------------------------------------
}