<?php

namespace action\developer\general\functions;

/**
 * Class xedit
 * @package action\system\setup\acl_role\functions
 * @author Ryno Van Zyl
 */

class xload_config_ini implements \com\router\int\action {
    //--------------------------------------------------------------------------------
	use \com\router\tra\action;
    //--------------------------------------------------------------------------------
    public function auth() { return \core::$app->get_token()->check("dev");
}
    //--------------------------------------------------------------------------------
    public function run () {

    	$install = \app\coder\config\ini_file::make();

    	if(!file_exists($install::$filename))
    		return \app\http::ajax_response(["alert" => "Config file does not exist"]);
    	
    	$save_setting = function($group, $id, $property_key)use($install){
    		$value = $install->get_option($group, $id);
    		if(!isempty($value)) \db_settings::save_setting($property_key, $value);
		};
    	
    	$save_setting("standard", "company", SETTING_COMPANY_NAME);
		$save_setting("standard", "website", SETTING_COMPANY_WEBSITE);
		$save_setting("custom", "app.currency.remove.decimals", SETTING_APP_CURRENCY_REMOVE_DECIMALS);
		$save_setting("custom", "app.currency.vat.amount", SETTING_APP_CURRENCY_VAT_AMOUNT);
		$save_setting("custom", "email.no_reply", SETTING_COMPANY_EMAIL_NO_REPLY);
		$save_setting("custom", "email.from", SETTING_COMPANY_EMAIL_FROM);
		$save_setting("custom", "email.accounts", SETTING_COMPANY_EMAIL_ACCOUNTS);
		$save_setting("custom", "email.contact", SETTING_COMPANY_EMAIL_CONTACT);
		$save_setting("custom", "email.admin", SETTING_COMPANY_EMAIL_ADMIN);
		$save_setting("custom", "email.order", SETTING_COMPANY_EMAIL_ORDER);
		$save_setting("custom", "email.support", SETTING_COMPANY_EMAIL_SUPPORT);
		$save_setting("custom", "email.repairs", SETTING_COMPANY_EMAIL_REPAIRS);
		$save_setting("custom", "email.sales", SETTING_COMPANY_EMAIL_SALES);
		$save_setting("custom", "email.appointment", SETTING_COMPANY_EMAIL_APPOINTMENT);
		$save_setting("custom", "tell.nr.office", SETTING_COMPANY_TELLNR_OFFICE);
		$save_setting("custom", "tell.nr.contact", SETTING_COMPANY_TELLNR_CONTACT);
		$save_setting("custom", "tell.nr.general", SETTING_COMPANY_TELLNR_GENERAL);
		$save_setting("custom", "tell.nr.sales", SETTING_COMPANY_TELLNR_SALES);
		$save_setting("custom", "tell.nr.fax", SETTING_COMPANY_TELLNR_FAX);
		$save_setting("custom", "company.reg_no", SETTING_COMPANY_REG_NO);
		$save_setting("custom", "company.vat_no", SETTING_COMPANY_VAT_NO);

		$save_setting("custom", "bs.color.primary", SETTING_BS_PRIMARY);
		$save_setting("custom", "bs.color.secondary", SETTING_BS_SECONDARY);
		$save_setting("custom", "bs.color.success", SETTING_BS_SUCCESS);
		$save_setting("custom", "bs.color.info", SETTING_BS_INFO);
		$save_setting("custom", "bs.color.danger", SETTING_BS_DANGER);
		$save_setting("custom", "bs.color.warning", SETTING_BS_WARNING);
		$save_setting("custom", "bs.color.light", SETTING_BS_LIGHT);
		$save_setting("custom", "bs.color.dark", SETTING_BS_DARK);

		return \app\http::ajax_response([
			"notice" => "Config Loaded Successfully",
			"js" => "mod.refresh();",
		]);

    }
    //--------------------------------------------------------------------------------
}

