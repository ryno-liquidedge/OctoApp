<?php

namespace LiquidedgeApp\Octoapp\app\app\session;

/**
 * @package app
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class session extends \LiquidedgeApp\Octoapp\app\app\intf\standard {
	//--------------------------------------------------------------------------------
	public static function get_session_var($key, $fn, $options = []) {
	    $options = array_merge([
	        "force" => false,
	    ], $options);

		$session = \core::$app->get_session();

		if($session){
            $result = $session->get($key);
            if(!$result || $options["force"]){
                $result = $session->{$key} = $fn();
            }
        }else{
		    $result = $fn();
        }

        return $result;
	}
	//--------------------------------------------------------------------------------
	public static function set_session_var($key, $value, $options = []) {

		$session = \core::$app->get_session();

		if($session) $session->{$key} = $value;
	}
	//--------------------------------------------------------------------------
}