<?php

namespace action\developer\functions;

/**
 * Class xadd
 * @package action\system\setup\person_type\functions
 * @author Ryno Van Zyl
 */


class xrun_octoapi_setup implements \com\router\int\action {
    //--------------------------------------------------------------------------------
	use \com\router\tra\action;
    //--------------------------------------------------------------------------------
    public function auth() { return \core::$app->get_token()->check("dev");
}
    //--------------------------------------------------------------------------------
    public function run () {

    	$api = \app\api\octoapi\api::make();
		$response = $api->company()->get()->setup();

		$response_data_arr = $response->get_response_data();
		if($response_data_arr && !$response->has_error()){

			$fn_map_setting = function($key, $octo_key)use($response){
				$response_data_arr = $response->get_response_data();
				$value = $response->extract_from_arr($response_data_arr, $octo_key);
				if($value) \db_settings::save_setting($key, $value);
			};

			$fn_map_setting(SETTING_COMPANY_NAME, "octo.company.name");
			$fn_map_setting(SETTING_COMPANY_TELLNR_WORK, "octo.company.telnr_work");
			$fn_map_setting(SETTING_COMPANY_TELLNR_FAX, "octo.company.faxnr");
			$fn_map_setting(SETTING_COMPANY_EMAIL, "octo.company.email");
			$fn_map_setting(SETTING_COMPANY_PREFIX, "octo.company.prefix");
			$fn_map_setting(SETTING_TERMS_AND_CONDITIONS, "octo.company.terms_and_conditions");

		}

    }
    //--------------------------------------------------------------------------------
}

