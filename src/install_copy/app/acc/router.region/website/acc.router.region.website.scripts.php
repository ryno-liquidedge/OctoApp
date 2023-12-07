<?php

namespace acc\router\region\website;

/**
 * @package acc\router\region\website
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class scripts implements \com\router\int\region {
	//--------------------------------------------------------------------------------
	use \com\router\tra\region;
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function render() {

	    $section = \core::$app->get_section()->get_class();
	    $js_version = \com\asset::get_js_version();

	    $html = \com\ui::make()->buffer();

//	    $html->script(false, ["@type" => "text/javascript", "@src" => "https://cdn.jsdelivr.net/npm/fullcalendar/index.global.min.js"]);
//	    $html->script(false, ["@type" => "text/javascript", "@src" => "https://cdn.jsdelivr.net/npm/@fullcalendar/bootstrap5@6.1.8/index.global.min.js"]);
//	    $html->script(false, ["@type" => "text/javascript", "@src" => \app\http::get_stream_url(\core::$folders->get_app_app()."/ui/inc/js/fullcalendar-bs5-addon.js")]);
	    $html->script(false, ["@type" => "text/javascript", "@src" => "index.php?c=index/xfile&context=website&name=js&v={$js_version}"]);
//	    $html->script(false, ["@type" => "text/javascript", "@src" => "https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"]);
	    $html->add(\LiquidedgeApp\Octoapp\app\app\js\js::get_script());
	    $html->add(\LiquidedgeApp\Octoapp\app\app\js\js::get_domready());

	    $html->script(["*" => "
	        // placeholder function 
            window.onunload = function(){};
            $(function(){
            
                $('.display-on-load').each(function(){
                    let el = $(this);
                    let delay = el.data('delay');
                    
                    el.fadeIn(function(){
                        setTimeout(function(){
                            core.overlay.hide();
                            AOS.init();
                        }, delay ? parseInt(delay) : 0);
                    });
                });
            
            });
	    "]);

		return $html->get_clean();
	}
	//--------------------------------------------------------------------------------
	// static
	//--------------------------------------------------------------------------------
	/**
	 * @return \com\router\region\scripts
	 */
	public static function make($options = []) {
		return new static($options);
	}
	//--------------------------------------------------------------------------------
}