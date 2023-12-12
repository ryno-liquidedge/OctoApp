<?php

namespace action\developer\general\functions;

/**
 * Class xedit
 * @package action\system\setup\acl_role\functions
 * @author Ryno Van Zyl
 */

class xedit implements \com\router\int\action {
    //--------------------------------------------------------------------------------
	use \com\router\tra\action;
    //--------------------------------------------------------------------------------
    public function auth() { return \core::$app->get_token()->check("dev");
}
    //--------------------------------------------------------------------------------
    public function run () {

        message(false);

        \app\solid::install();

        \app\install\acl_role\make\install::install_from_db();
        \app\install\acl_role\make\install::install_from_solid();
        \app\install\acl_role\make\install::build_constants();

        \app\install\person_type\make\install::install_from_db();
        \app\install\person_type\make\install::install_from_solid();
        \app\install\person_type\make\install::build_constants();


		$config_arr = [];
        $settings_arr = $this->request->get('settings_arr', \com\data::TYPE_STRING, ["default" => []]);

        foreach ($settings_arr as $id => $key){
            $solid = \app\solid::get_setting_instance($key);
            $value = $this->request->get($id, $solid->get_data_type(), ["default" => $solid->get_default()]);
            $solid->save_value($value);

            $config_arr[$key] = $value;
        }

        $fn_get_config_val = function($key)use($config_arr){
        	if(isset($config_arr[$key])) return $config_arr[$key];
        	return "";
		};

        //install ini config file
		$ini_file = \app\coder\config\ini_file::make();
		$ini_file->set_option("standard", "company", $fn_get_config_val(SETTING_COMPANY_NAME));
		$ini_file->set_option("standard", "title", $fn_get_config_val(SETTING_WEBSITE_TITLE));
		$ini_file->set_option("standard", "website", $fn_get_config_val(SETTING_COMPANY_WEBSITE));
		$ini_file->set_option("custom", "app.currency.remove.decimals", $fn_get_config_val(SETTING_APP_CURRENCY_REMOVE_DECIMALS));
		$ini_file->set_option("custom", "app.currency.vat.amount", $fn_get_config_val(SETTING_APP_CURRENCY_VAT_AMOUNT));
		$ini_file->set_option("custom", "email.no_reply", $fn_get_config_val(SETTING_COMPANY_EMAIL_NO_REPLY));
		$ini_file->set_option("custom", "email.from", $fn_get_config_val(SETTING_COMPANY_EMAIL_FROM));
		$ini_file->set_option("custom", "email.accounts", $fn_get_config_val(SETTING_COMPANY_EMAIL_ACCOUNTS));
		$ini_file->set_option("custom", "email.contact", $fn_get_config_val(SETTING_COMPANY_EMAIL_CONTACT));
		$ini_file->set_option("custom", "email.admin", $fn_get_config_val(SETTING_COMPANY_EMAIL_ADMIN));
		$ini_file->set_option("custom", "email.order", $fn_get_config_val(SETTING_COMPANY_EMAIL_ORDER));
		$ini_file->set_option("custom", "email.support", $fn_get_config_val(SETTING_COMPANY_EMAIL_SUPPORT));
		$ini_file->set_option("custom", "email.repairs", $fn_get_config_val(SETTING_COMPANY_EMAIL_REPAIRS));
		$ini_file->set_option("custom", "email.sales", $fn_get_config_val(SETTING_COMPANY_EMAIL_SALES));
		$ini_file->set_option("custom", "email.appointment", $fn_get_config_val(SETTING_COMPANY_EMAIL_APPOINTMENT));
		$ini_file->set_option("custom", "tell.nr.office", $fn_get_config_val(SETTING_COMPANY_TELLNR_OFFICE));
		$ini_file->set_option("custom", "tell.nr.contact", $fn_get_config_val(SETTING_COMPANY_TELLNR_CONTACT));
		$ini_file->set_option("custom", "tell.nr.general", $fn_get_config_val(SETTING_COMPANY_TELLNR_GENERAL));
		$ini_file->set_option("custom", "tell.nr.sales", $fn_get_config_val(SETTING_COMPANY_TELLNR_SALES));
		$ini_file->set_option("custom", "tell.nr.fax", $fn_get_config_val(SETTING_COMPANY_TELLNR_FAX));
		$ini_file->set_option("custom", "company.reg_no", $fn_get_config_val(SETTING_COMPANY_REG_NO));
		$ini_file->set_option("custom", "company.vat_no", $fn_get_config_val(SETTING_COMPANY_VAT_NO));

		$ini_file->set_option("custom", "bs.color.primary", $fn_get_config_val(SETTING_BS_PRIMARY));
		$ini_file->set_option("custom", "bs.color.secondary", $fn_get_config_val(SETTING_BS_SECONDARY));
		$ini_file->set_option("custom", "bs.color.success", $fn_get_config_val(SETTING_BS_SUCCESS));
		$ini_file->set_option("custom", "bs.color.info", $fn_get_config_val(SETTING_BS_INFO));
		$ini_file->set_option("custom", "bs.color.danger", $fn_get_config_val(SETTING_BS_DANGER));
		$ini_file->set_option("custom", "bs.color.warning", $fn_get_config_val(SETTING_BS_WARNING));
		$ini_file->set_option("custom", "bs.color.light", $fn_get_config_val(SETTING_BS_LIGHT));
		$ini_file->set_option("custom", "bs.color.dark", $fn_get_config_val(SETTING_BS_DARK));


		$address = \db_settings::get_company_address();
		if($address) $ini_file->set_option("custom", "company.address.physical.str", $address->format_lines(["delimiter" => ", "]));
		$ini_file->build();

        //attempt so override bootstrap colors
		\LiquidedgeApp\Octoapp\app\app\coder\scss\variables_compiler::make()->run();

        message(true, "Changes Saved");
    }
    //--------------------------------------------------------------------------------
}

