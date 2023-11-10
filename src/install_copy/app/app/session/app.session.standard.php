<?php

namespace app\session;

/**
 * @package app\session
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 *
 * init
 * $app_session = \app\session\standard::make();
 * $app_session->person = \core::dbt("person")->splat(\com\user::$active_id);
 * $app_session->update();
 *
 * usage
 * view($app_session->person);
 *
 */

class standard extends \LiquidedgeApp\Octoapp\app\app\session\standard {

	public $name = "standard";
    //--------------------------------------------------------------------------
}
