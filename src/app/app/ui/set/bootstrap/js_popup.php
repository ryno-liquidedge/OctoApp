<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class js_popup extends \com\ui\intf\element {
	//--------------------------------------------------------------------------------
	// properties
	//--------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	public function __construct($options = []) {

		// init
        $this->name = "JS Popup";

	}
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function build($options = []) {
        $options = array_merge([
            "url" => false,
            "fullscreen" => false,
            "panel" => \core::$app->get_request()->get_panel(),

            "*class" => null,
            "*height_class" => false,
            "*width" => "modal-lg",
            "*title" => "Alert",
            "*hide_header" => true,
            "*hide_footer" => true,
            "*enable_loading_content" => true,
            "*closable" => false,
            "*backdrop" => "static", // true | false | 'static',
            "*fade_class" => "fade-in",
        ], $options);

        if($options["fullscreen"]){
            $options["*width"] = "modal-fullscreen";
            $options["*class"] = "my-0";
            $options["*height_class"] = "{$options["*height_class"]} min-vh-100";
        }

        if(!$options["*height_class"]){
            $options["*height_class"] = "min-h-40vh";
        }

        $js_options = \LiquidedgeApp\Octoapp\app\app\js\js::create_options($options);

        return "{$options["panel"]}.popup('{$options["url"]}', {$js_options});";
	}
	//--------------------------------------------------------------------------------
}