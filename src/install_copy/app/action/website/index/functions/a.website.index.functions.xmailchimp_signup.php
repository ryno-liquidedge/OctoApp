<?php

namespace action\website\index\functions;

/**
 * @package action\website\index
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class xmailchimp_signup implements \com\router\int\action {

    //--------------------------------------------------------------------------------
	use \com\router\tra\action;
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		// options
		$options = array_merge([
		], $options);

		\core::$app->set_section(\acc\core\section\no_audit::make());
	}
    //--------------------------------------------------------------------------------
    public function auth() { 
        return true;
    }
    //--------------------------------------------------------------------------------
    public function run () {

		$empty_check = $this->request->get('empty_check', \com\data::TYPE_STRING);
		$mc_email = $this->request->get('mc_email', \com\data::TYPE_STRING);
		$mc_firstname = $this->request->get('mc_firstname', \com\data::TYPE_STRING);
		$mc_lastname = $this->request->get('mc_lastname', \com\data::TYPE_STRING);
		$form_target = $this->request->get('form_target', \com\data::TYPE_STRING);

		if($empty_check) return \app\http::ajax_response(["redirect" => \app\http::get_seo_url("ui_error", 404)]);

		$api_key = \db_settings::get_value(SETTING_MAILCHIMP_API_KEY, ["force" => true]);
		$server_prefix = \db_settings::get_value(SETTING_MAILCHIMP_SERVER_PREFIX, ["force" => true]);
		$list_id = \db_settings::get_value(SETTING_MAILCHIMP_AUDIENCE_ID, ["force" => true]);

		if(isempty($api_key) || isempty($server_prefix) || isempty($list_id))
			return \app\http::ajax_response(["alert" => "Mail server setup has not been completed yet"]);

		$mailchimp = new \MailchimpMarketing\ApiClient();

		$mailchimp->setConfig([
		  'apiKey' => $api_key,
		  'server' => $server_prefix
		]);

		try{
			$mailchimp->lists->addListMember($list_id, [
				"email_address" => $mc_email,
				"status" => "subscribed",
				"merge_fields" => [
				  "FNAME" => $mc_firstname,
				  "LNAME" => $mc_lastname,
				]
			], false);

		}catch(\Exception $ex){
			\com\error::create($ex->getResponse()->getBody()->getContents());
		}

		return \app\http::ajax_response([
			"title" => "Subscribed Successfully",
			"alert" => "Thank you for subscribing",
			"js" => "core.form.clear_fields({form_target:'#{$form_target}'})",
		]);

    }
    //--------------------------------------------------------------------------------
}

