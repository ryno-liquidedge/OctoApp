<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * @package app\ui\set\bootstrap
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 *
 * https://github.com/INTELOGIE/signature_pad
 *
 */
class isignature extends \LiquidedgeApp\Octoapp\app\app\ui\intf\element {
	//--------------------------------------------------------------------------------
	// fields
	//--------------------------------------------------------------------------------
	protected static $is_singleton = true;

	protected $options = null;
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct() {
		$this->name = "Input Signature";
	}
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function build($options = []) {

	    $options = array_merge([
	        "id" => false,
	        "value" => false,
	        "width" => 1200,
	        "height" => 600,
	        "!complete" => "function(ev){}",
	        "!start" => "function(ev){}",
	        "!clear" => "function(ev){}",

	        "/canvas" => [],

            "js_id" => \LiquidedgeApp\Octoapp\app\app\str\str::get_random_id("signature")
	    ], $options);

		$id = $options["id"];
		$value = $options["value"];
		$js_id = $options["js_id"];

        $buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();
        $buffer->style(["*" => "
            .ui-signature-wrapper{
                border-width: 1px;
                position: relative;
                overflow:hidden;
                border-radius: 5px;
                border-color: var(--dark);
                border-style: dashed;
            }
            .ui-signature-canvas{
                cursor: crosshair;
                display: block;
                border-radius: 1rem;
            }
            .btn-signature-canvas.btn.btn-sm{
                font-size: 0.7rem !important;
                padding: 7px !important;
            }
            .ui-signature-toolbar{
                position:absolute;
                top:-50px;
                right:0px;
                transition: all 0.2s ease-in-out;
            }
            .ui-signature-wrapper:hover .ui-signature-toolbar{
                display:block;
                top:5px;
            }
        "]);

        if(\LiquidedgeApp\Octoapp\app\app\http\http::is_mobile()){
            $buffer->style(["*" => "
                .ui-signature-toolbar{
                    position:absolute;
                    display:block;
                    top:5px;
                    right:0px;
                }
            "]);
        }

        $buffer->div_([".ui-signature-wrapper" => true]);
            $buffer->div_([".ui-signature-toolbar" => true]);
                $toolbar = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->toolbar();
                $toolbar->add(\LiquidedgeApp\Octoapp\app\app\ui\ui::make()->button(false, false, ["icon" => "fa-undo", ".btn-warning btn-sm btn-signature-canvas undo-canvas-{$js_id} d-none" => true]));
                $toolbar->add(\LiquidedgeApp\Octoapp\app\app\ui\ui::make()->button(false, false, ["icon" => "fa-ban", ".btn-danger btn-sm btn-signature-canvas clear-canvas-{$js_id}" => true]));
                $buffer->add($toolbar->build());
            $buffer->_div();

            $canvas = $options["/canvas"];
            $canvas[".ui-signature-canvas"] = true;
            $canvas["@width"] = $options["width"];
            $canvas["@height"] = $options["height"];
            $canvas["@id"] = $js_id;

            $buffer->xihidden($id, $value);
            $buffer->canvas($canvas);

        $buffer->_div();

        $buffer->script([
            "@src" => "https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js",
        ]);

        $buffer->script(["*" => "
            var {$id};
            
            $(function(){
                let canvas = document.getElementById('{$js_id}');
                
                {$id} = new SignaturePad(canvas, {
                    backgroundColor: '#FFF',
                    minWidth: '0.5',
                    maxWidth: '2',
                    onBegin: function(ev){
						$('.undo-canvas-{$js_id}').removeClass('d-none');
					  
						let on_start = {$options["!start"]};
						on_start.apply(this, [ev]);
					},
					onEnd: function(ev){
						$('#$id').val({$id}.toDataURL());
					  
						let on_complete = {$options["!complete"]};
						on_complete.apply(this, [ev]);
					},
                });
                
                
                {$id}.clearCanvas = function(ev){
                    $('#$id').val('');
                    let clear = {$options["!clear"]};
                    clear.apply(this, [ev]);
                    
                    this.clear();
                }
                
                ".($value ? "{$id}.fromDataURL('{$value}');" : "")."
                
                $('body').on('click', '.clear-canvas-{$js_id}', function(){
                    {$id}.clearCanvas();
                });
                
                $('body').on('click', '.undo-canvas-{$js_id}', function(){
                    var data = {$id}.toData();
                    if (data) {
                        data.pop();
                        {$id}.fromData(data);
                    }
                });
                
            });
            
        "]);



        return $buffer->build();
	}
	//--------------------------------------------------------------------------------
}