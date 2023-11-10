<?php

namespace acc\router\region\system;

/**
 * @package acc\router\region\system
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class banner implements \com\router\int\region {
	//--------------------------------------------------------------------------------
	use \com\router\tra\region;
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function render() {

		// html
		$buffer = \com\ui::make()->buffer();

		$buffer->div_(".nova-banner .p-2");
			$buffer->div_([".container-fluid" => true, ]);
				$buffer->div_([".row mt-2 align-items-center" => true, ]);
					$buffer->div_([".col" => true]);
						$buffer->ximage(\app\http::get_stream_url(\db_settings::get_logo()), ["@alt" => "logo", ".max-w-200px" => true]);
					$buffer->_div();
					$buffer->div_([".col" => true, ".text-end" => true, ]);
						$buffer->span_();
							$buffer->ul_([".list-inline font-14" => true]);
								$buffer->li_([".list-inline-item" => true]);
									$dropdown_user = \com\ui::make()->dropdown();
									if(\com\user::$active_role == "DEV") $dropdown_user->add_link("Developer", "?c=developer/vmanage", ["icon" => "fa-cog"]);

									if(\core::$app->get_token()->check("admins")){
                                        $dropdown_user->add_link("Go to Website", \app\http::get_seo_url("ui_home", false, ["absolute_url" => true]), ["icon" => "fa-sign-out-alt", "@target" => "_blank"]);
                                    }
									if (\core::$app->get_token()->get_loginas_id()) {
										$dropdown_user->add_link("Revert login as", "?c=index/xloginas_revert", ["icon" => "repeat"]);
									}
									$dropdown_user->add_link("Logout", "?c=index/xlogout", ["icon" => "fa-external-link-alt"]);
									$buffer->add("Welcome ");
									$buffer->xlink($dropdown_user, \com\user::$user->format_name(), [".dropdown-toggle" => true]);

								$buffer->_li();
								$buffer->li_([".list-inline-item" => true]);
									// role dropdown
									$csrf = \core::$app->get_response()->get_csrf();
									$role_arr = \com\user::$user->get_role_list();
									$dropdown_role = \com\ui::make()->dropdown([".dropstart" => true]);
									$dropdown_role->add_header("Change Role to");
									foreach ($role_arr as $role_index => $role_item) {
										$dropdown_role->add_link($role_item, "javascript:core.overlay.show('body'); core.ajax.request_function('?c=index/xrole&role={$role_index}', function() { document.location = '?c=index/xhome'; }, {  csrf: '{$csrf}' });");
									}

									$buffer->span(["*" => "You are logged in as "]);
									$buffer->xlink($dropdown_role, $role_arr[\com\user::$user_role], [".dropdown-toggle" => true]);
								$buffer->_li();
							$buffer->_ul();
						$buffer->_span();
					$buffer->_div();
				$buffer->_div();
			$buffer->_div();
		$buffer->_div();

		// done
		return $buffer->get_clean();
	}
	//--------------------------------------------------------------------------------
	// static
	//--------------------------------------------------------------------------------
	/**
	 * @param array $options
	 * @return static
	 */
	public static function make($options = []) {
		return new static($options);
	}
	//--------------------------------------------------------------------------------
}