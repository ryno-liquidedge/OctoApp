<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class icon_toggle_password extends \com\ui\intf\element {
	//--------------------------------------------------------------------------------
	// fields
	//--------------------------------------------------------------------------------
	protected static $is_singleton = true;

	protected $options = null;
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct() {
		$this->name = "Icon Toggle Password";
	}
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function build($options = []) {

		$options = array_merge([
		    "@title" => "Toggle Visibility",
		    ".toggle-password-visibility cursor-pointer" => true,
			"@data-target" => false,
			".me-2" => false,
		], $options);

		$uid = \core::$app->get_session()->session_uid;
		$options[".uid-$uid"] = true;

		$buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();
		$buffer->xicon("fa-eye-slash", $options);

		\LiquidedgeApp\Octoapp\app\app\js\js::add_script("
        	$(function(){
        		$('body').on('click', '.uid-$uid', function(){
        		    let element = $(this);
        		    let targets = element.data('target');
        		    let target_arr = targets.split(',');
        		    let is_visible = element.hasClass('fa-eye');
        		    
        		    target_arr.forEach(function(item, index){
        		    	if(!is_visible){
        		    		$(item).attr('type', 'text');
        		    		element.removeClass('fa-eye-slash').addClass('fa-eye');
        		    	}else{
        		    		$(item).attr('type', 'password');
        		    		element.addClass('fa-eye-slash').removeClass('fa-eye');
        		    	}
        		    })
        		})
        	})
        ");

		return $buffer->get_clean();
	}
	//--------------------------------------------------------------------------------
}