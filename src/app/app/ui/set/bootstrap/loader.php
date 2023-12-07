<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * @package app\ui\set\bootstrap
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class loader extends \LiquidedgeApp\Octoapp\app\app\ui\intf\element {

	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		// init
		$this->name = "Loader";
	}
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
    public function build($options = []) {

        $options["@id"] = \LiquidedgeApp\Octoapp\app\app\str\str::get_random_id("loader");
        $options[".page-loader-inner"] = true;

        $buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();
        $buffer->style(["*" => "
        	.page-loader-overlay {
				left: 0;
				top: 0;
				width: 100%;
				height: 100%;
				position: fixed;
				background: rgb(41 41 41 / 76%);
				z-index: 9999;
			}
			
			.page-loader-inner {
				left: 0;
				top: 0;
				width: 100%;
				height: 100%;
				position: absolute;
			}
			
			.page-loader-content {
				left: 50%;
				position: absolute;
				top: 50%;
				transform: translate(-50%, -50%);
			}
			
			.page-loader-spinner {
				width: 75px;
				height: 75px;
				display: inline-block;
				border-width: 2px;
				border-color: rgba(255, 255, 255, 0.05);
				border-top-color: #fff;
				animation: page_loader_spin 1s infinite linear;
				border-radius: 100%;
				border-style: solid;
			}
			
			@keyframes page_loader_spin {
			  100% {
				transform: rotate(360deg);
			  }
			}
        "]);

        $buffer->div_([".page-loader-overlay" => true]);
			$buffer->div_([".page-loader-inner" => true]);
				$buffer->div_([".page-loader-content" => true]);
					$buffer->span([".page-loader-spinner" => true]);
				$buffer->_div();
			$buffer->_div();
        $buffer->_div();

        \LiquidedgeApp\Octoapp\app\app\js\js::add_script("
            $(function(){
                setTimeout(function(){
                    core.overlay.hide();
                }, 400);
            });
        ");

        return $buffer->get_clean();
    }
	//--------------------------------------------------------------------------------
}