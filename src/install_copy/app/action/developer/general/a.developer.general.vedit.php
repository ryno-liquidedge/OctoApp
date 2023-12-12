<?php

namespace action\developer\general;

/**
 * Class vedit
 * @package action\system\setup\acl_role
 * @author Ryno Van Zyl
 */
class vedit implements \com\router\int\action {
	//--------------------------------------------------------------------------------
	use \com\router\tra\action;

	//--------------------------------------------------------------------------------
	public function auth() {
		return \core::$app->get_token()->check("dev");
	}

	//--------------------------------------------------------------------------------
	public function run() {

		// html
		$buffer = \app\ui::make()->html_buffer();
		$buffer->header(3, "General Settings");
		$buffer->form("?c=developer.general.functions/xedit");
		$buffer->submitbutton("Save Changes", false, "requestRefresh", false, false, false, ["icon" => "fa-save", ".btn-success" => true], "core.browser.close_popup();");
		$buffer->button("Load Config.ini File", \app\js::ajax("?c=developer.general.functions/xload_config_ini", [
			"*confirm" => "This will overwrite your current settings. Are you sure you want to continue?"
		]), ["icon" => "fa-refresh", ".btn-warning" => true]);

		$options = ["label_col" => 5];

		// header
		$buffer->div_([".row my-4" => true]);
			$buffer->div_([".col-12 col-md-6" => true]);

				$buffer->xfieldset("System Config", function($buffer)use($options){
					$buffer->xisetting(SETTING_COMPANY_NAME, $options);
					$buffer->xisetting(SETTING_WEBSITE_TITLE, $options);
					$buffer->xisetting(SETTING_COMPANY_TELLNR_WORK, $options);
					$buffer->xisetting(SETTING_COMPANY_TELLNR_CONTACT, $options);
					$buffer->xisetting(SETTING_COMPANY_TELLNR_FAX, $options);
					$buffer->xisetting(SETTING_COMPANY_EMAIL, $options);
					$buffer->xisetting(SETTING_COMPANY_WEBSITE, $options);

					$buffer->xisetting(SETTING_APP_CURRENCY_REMOVE_DECIMALS, $options + ["inline" => true]);
					$buffer->xisetting(SETTING_APP_CURRENCY_VAT_AMOUNT, $options);
				});

				$buffer->xfieldset("Address", function($buffer){
					$address = \db_settings::get_company_address(["force" => true]);
					$buffer->xiaddress($address, ["type" => false]);
				});

				$buffer->xfieldset("Mailchimp", function($buffer)use($options){
					$buffer->xisetting(SETTING_MAILCHIMP_API_KEY, $options);
					$buffer->xisetting(SETTING_MAILCHIMP_SERVER_PREFIX, $options);
					$buffer->xisetting(SETTING_MAILCHIMP_AUDIENCE_ID, $options);
				});

				if(defined("SETTING_OCTOAPI_SERVICE_URL")){
					$buffer->xfieldset("Octo API", function($buffer)use($options){
						$buffer->xisetting(SETTING_OCTOAPI_SERVICE_URL, $options);
						$buffer->xisetting(SETTING_OCTOAPI_SERVICE_USERNAME, $options);
						$buffer->xisetting(SETTING_OCTOAPI_SERVICE_PASSWORD, $options);
						$buffer->xbutton("Run Setup", "{$this->request->get_panel()}.requestRefresh('?c=developer.functions/xrun_octoapi_setup')", [".w-100 mt-3" => true]);
					});
				}
			$buffer->_div();
			$buffer->div_([".col-12 col-md-6" => true]);
				$buffer->xfieldset("System Style", function($buffer)use($options){

					$color_input = function($key)use(&$buffer, $options){
						$solid = \app\solid::get_setting_instance($key);
						$buffer->xicolor($solid->get_form_id(), $solid->get_value(["force" => true]), $solid->get_display_name(), $options);
						$buffer->xihidden("settings_arr[{$solid->get_form_id()}]", $solid->get_key());
					};

					$color_input(SETTING_BS_PRIMARY);
					$color_input(SETTING_BS_SECONDARY);
					$color_input(SETTING_BS_SUCCESS);
					$color_input(SETTING_BS_INFO);
					$color_input(SETTING_BS_WARNING);
					$color_input(SETTING_BS_DANGER);
					$color_input(SETTING_BS_LIGHT);
					$color_input(SETTING_BS_DARK);

				}, [
					"dropdown" => function(){
						$dropdown = \app\ui::make()->dropdown();
						$dropdown->add_button("Reset Colors", "{$this->request->get_panel()}.requestRefresh('?c=developer.general.functions/xreset_bs_colors', {confirm: true})", ["@class" => "dropdown-item btn"]);
						return $dropdown;
					}
				]);

				$buffer->xfieldset("Email (Advanced)", function($buffer)use($options){
					$buffer->xisetting(SETTING_COMPANY_EMAIL_ACCOUNTS, $options);
					$buffer->xisetting(SETTING_COMPANY_EMAIL_ADMIN, $options);
					$buffer->xisetting(SETTING_COMPANY_EMAIL_APPOINTMENT, $options);
					$buffer->xisetting(SETTING_COMPANY_EMAIL_CONTACT, $options);
					$buffer->xisetting(SETTING_COMPANY_EMAIL_FROM, $options);
					$buffer->xisetting(SETTING_COMPANY_EMAIL_NO_REPLY, $options);
					$buffer->xisetting(SETTING_COMPANY_EMAIL_ORDER, $options);
					$buffer->xisetting(SETTING_COMPANY_EMAIL_REPAIRS, $options);
					$buffer->xisetting(SETTING_COMPANY_EMAIL_SALES, $options);
					$buffer->xisetting(SETTING_COMPANY_EMAIL_SUPPORT, $options);
					$buffer->xisetting(SETTING_COMPANY_EMAIL_TEST_CC, $options);
				});

				$buffer->xfieldset("External Publishing", function($buffer)use($options){
					$buffer->xisetting(SETTING_EXTERNAL_PUBLISHING_PLATFORM_ENABLE_GUMTREE, $options + ["inline" => true]);
					$buffer->xisetting(SETTING_EXTERNAL_PUBLISHING_PLATFORM_ENABLE_PRIVATE_PROPERTY, $options + ["inline" => true]);
				});

			$buffer->_div();

		$buffer->_div();

		$buffer->flush();

	}
	//--------------------------------------------------------------------------------
}

