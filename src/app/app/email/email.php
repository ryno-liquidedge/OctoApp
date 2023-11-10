<?php

namespace LiquidedgeApp\Octoapp\app\app\email;

/**
 * Class email
 * @package app
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class email extends \LiquidedgeApp\Octoapp\app\app\intf\standard {

    //--------------------------------------------------------------------------------
	/**
	 * @param $person \db_person
	 * @param array $options
	 * @return \com\db\row|\com\db\table|\db_email|false
	 */
	public static function send_account_verification_email($person, $options = []) {

		//create request entry
        $request = \core::dbt("request")->find([
            ".req_type" => 2,
            ".req_ref_reference" => $person->per_id,
            "create" => true,
        ]);
        $request->req_data = \LiquidedgeApp\Octoapp\app\app\http\http::get_seo_url("ui_message", 106, ["absolute_url" => true]);
        $request->req_json = json_encode($person->get_array());
        $request->save();

        //build url
        $url = \core::$app->get_instance()->get_url()."/index.php?c=website.index.functions/xverify_account&req_code={$request->req_code}";

		//send email
        $email = \LiquidedgeApp\Octoapp\app\app\email\factory\builder::make();
		$email->set_subject("Verify Account");
		$email->set_force(true);
		$email->set_to($person);
		$email->set_content(function($email) use($url){
			//build body
			$buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();
			$buffer->p(["*" => "Thank you for registering and welcome to {$email->company}."]);
			$buffer->p_();
				$buffer->add("Please '")->xlink($url, "click here")->add("' to complete your registration.");
			$buffer->_p();
			$buffer->p(["*" => "We trust that you will have a great experience with us."]);

			return $buffer->build();
		});

		return $email->send();

	}
    //--------------------------------------------------------------------------------
	/**
	 * @param $person \db_person
	 * @param array $options
	 * @return \com\db\row|\com\db\table|\db_email|false
	 */
	public static function send_reset_password_email($person, $options = []) {

		//create request entry
        $request = \core::dbt("request")->find([
            ".req_type" => 1,
            ".req_ref_reference" => $person->per_id,
            "create" => true,
        ]);
        $request->req_data = \LiquidedgeApp\Octoapp\app\app\http\http::get_seo_url("ui_change_password", false, ["absolute_url" => true]);
        $request->save();

        //build url
        $url = \core::$app->get_instance()->get_url()."/index.php?c=website.index.functions/xverify_account&req_code={$request->req_code}";

		//send email
        $email = \LiquidedgeApp\Octoapp\app\app\email\factory\builder::make();
		$email->set_force(true);
		$email->set_subject("Reset Password");
		$email->set_to($person);
		$email->set_content(function($email) use($url){

			//build body
			$buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();
			$buffer->p(["*" => "You have requested to reset your password on your {$email->company} account:"]);
			$buffer->p_();
				$buffer->add("Please '")->xlink($url, "click here")->add("' to reset your password.");
			$buffer->_p();
			$buffer->p(["*" => "We trust that you will have a great experience with us."]);

			return $buffer->build();
		});

		return $email->send();

	}
    //--------------------------------------------------------------------------------
	/**
	 * @param $person
	 * @param $message
	 * @param array $options
	 * @return \com\db\row|\com\db\table|\db_email|false
	 */
	public static function email_contact_us_admin($person, $message, $options = []) {

		$options = array_merge([
		    "name_to" => "Admin",
		    "email_to" => \core::$app->get_instance()->get_option("email.contact"),
		], $options);

		//send email
        $email = \LiquidedgeApp\Octoapp\app\app\email\factory\builder::make();
		$email->set_subject("Contact request from website");
		$email->set_to($options["email_to"], $options["name_to"]);
		$email->set_content(function($email)use($person, $message){

			//build body
			$buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();
			$buffer->p(["*" => "You have received an request via the website contact form."]);
			$buffer->p(["*" => "Please see the client information and message below."]);

			$buffer->strong(["*" => "Name: "])->add($person->per_firstname)->br();
			$buffer->strong(["*" => "Surname: "])->add($person->per_lastname)->br();
			$buffer->strong(["*" => "Email: "])->add($person->per_email)->br();
			if(!$person->is_empty("per_contactnr")) $buffer->strong(["*" => "Contact Nr: "])->add($person->per_contactnr)->br();
			if(!$person->is_empty("per_telnr_home")) $buffer->strong(["*" => "Tell Home: "])->add($person->per_telnr_home)->br();
			if(!$person->is_empty("per_telnr_work")) $buffer->strong(["*" => "Tell Work: "])->add($person->per_telnr_work)->br();
			if(!$person->is_empty("per_cellnr")) $buffer->strong(["*" => "Cell Nr: "])->add($person->per_cellnr)->br();
			$buffer->strong(["*" => "Message: "])->p(["*" => nl2br($message), ".comment" => true])->br()->br();

			return $buffer->build();
		});

		return $email->send();

	}
	//--------------------------------------------------------------------------------
	public static  function contact_agent_about_listing($listing, $person, $message) {

		$person_type = \core::dbt("person_type")->splat(PERSON_TYPE_INDIVIDUAL);
		$person->per_ref_person_type = $person_type->id;
		$person->person_type = $person_type;
		$listing_person_agent_arr = $listing->get_listing_person_agent_arr();

		//send email
        $email = \LiquidedgeApp\Octoapp\app\app\email\factory\builder::make();
		$email->set_subject("Enquiry Request for Property: {$listing->lis_key}");

		$first_index = \LiquidedgeApp\Octoapp\app\app\arr\arr::get_first_index($listing_person_agent_arr);
		foreach ($listing_person_agent_arr as $index => $listing_person_agent){
			if($first_index == $index) $email->set_to($listing_person_agent->person);
			else $email->add_cc($listing_person_agent->person);
		}

		$email->set_content(function($email)use($person, $message, $listing){

			$buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();

			$fn_row_item = function($title, $value)use(&$buffer){
				$buffer->div_(["." => true]);
					$buffer->strong(["*" => "{$title}: "])->add($value);
				$buffer->_div();
			};

			$buffer->p(["*" => "You have received a new enquiry with regards to a listing:"]);

			$buffer->p(["*" => "Listing Details:", "#font-size" => "14px", "#font-weight" => "bold", "#margin" => "0px"]);
			$fn_row_item("Listing Name", $listing->format_name());
			$fn_row_item("Link", \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->link( \LiquidedgeApp\Octoapp\app\app\http\http::get_seo_url("ui_listing_details", $listing, ["absolute_url" => true]), $listing->lis_key));

			$buffer->br();
			$buffer->p(["*" => "Client Details:", "#font-size" => "14px", "#font-weight" => "bold", "#margin" => "0px"]);
			$fn_row_item("Name", $person->per_firstname);
			$fn_row_item("Surname", $person->per_lastname);
			$fn_row_item("Email", $person->per_email);
			$fn_row_item("Cell Nr", $person->per_cellnr);

			$buffer->br();
			$buffer->p(["*" => "Client Details:", "#font-size" => "14px", "#font-weight" => "bold", "#margin" => "0px"]);
			$buffer->p(["*" => nl2br($message), ".comment" => true]);

			return $buffer->build();

		});

		return $email->send();
	}
	//--------------------------------------------------------------------------------

    /**
     * @param $person
     * @param array $options
     * @return \com\db\row|\com\db\table|\db_email|false
     */
	public static function email_contact_me($person, $options = []) {

		//send email
        $email = \LiquidedgeApp\Octoapp\app\app\email\factory\builder::make();
		$email->set_subject("Contact Me Request");
		$email->set_to(\core::$app->get_instance()->get_option("email.contact"), "Admin");
		$email->set_content(function($email)use($person){

			//build body
			$buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();
			$buffer->p(["*" => "You have received an request via the website \"Contact Me\" form."]);
			$buffer->p(["*" => "Please see the client information and message below."]);

			$buffer->strong(["*" => "Name: "])->add($person->per_firstname)->br();
			$buffer->strong(["*" => "Surname: "])->add($person->per_lastname)->br();
			$buffer->strong(["*" => "Email: "])->add($person->per_email)->br();
			if(!$person->is_empty("per_contactnr")) $buffer->strong(["*" => "Contact Nr: "])->add($person->per_contactnr)->br();
			if(!$person->is_empty("per_telnr_home")) $buffer->strong(["*" => "Tell Home: "])->add($person->per_telnr_home)->br();
			if(!$person->is_empty("per_telnr_work")) $buffer->strong(["*" => "Tell Work: "])->add($person->per_telnr_work)->br();
			if(!$person->is_empty("per_cellnr")) $buffer->strong(["*" => "Cell Nr: "])->add($person->per_cellnr)->br();

			return $buffer->build();
		});

		return $email->send();

	}
	//--------------------------------------------------------------------------------
  	public static function email_access_details($person, $password) {
		// params
		$person = \core::dbt("person")->splat($person);

		// check if we have the needed info
		if (!$person || !trim($password)) return;

		// check if user is active
		if (!$person->per_is_active) return;

		//send email
        $email = \LiquidedgeApp\Octoapp\app\app\email\factory\builder::make();
		$email->set_subject("Access details changed");
        $email->set_to($person);
		$email->set_content(function($email)use($person){

			//build body
			$buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();
			$buffer->p(["*" => "Dear {$person->format_name()}"]);
			$buffer->p(["*" => "Your access details for the account <b>{$person->per_username}</b> have been changed."]);
			$buffer->p(["*" => "Please keep your password safe."]);

			return $buffer->build();

		});

		return $email->send();

  	}
    //--------------------------------------------------------------------------------
  	public static function email_account_created($person) {
		// params
		$person = \core::dbt("person")->splat($person);

		// check if we have the needed info
		if (!$person) return;

		// check if user is active
		if (!$person->per_is_active) return;

		//send email
        $email = \LiquidedgeApp\Octoapp\app\app\email\factory\builder::make();
		$email->set_subject("New Account Created");
        $email->set_to($person);
		$email->set_content(function($email)use($person){

			//build body
			$buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();
			$buffer->p(["*" => "Dear {$person->format_name()}"]);
			$buffer->p(["*" => "Your access details for the account <b>{$person->per_username}</b> have been created."]);
			$buffer->p_();
				$buffer->add("Please '")->xlink("mailto:".\core::$app->get_instance()->get_option("email.accounts"), "click here")->add("' to contact your service administrator for your login credentials.");
			$buffer->_p();

			return $buffer->build();

		});

		return $email->send();

  	}
    //--------------------------------------------------------------------------------
  	public static function send_email($to, $subject, $fn_message) {

		//send email
        $email = \LiquidedgeApp\Octoapp\app\app\email\factory\builder::make();
		$email->set_subject($subject);
        $email->set_to($to);
		$email->set_content($fn_message); //function($email){}
		return $email->send();

  	}
    //--------------------------------------------------------------------------------
  	public static function email_account_activated($person) {
		// params
		$person = \core::dbt("person")->splat($person);

		// check if user is active
		if (!$person->per_is_active) return;

		//send email
        $email = \LiquidedgeApp\Octoapp\app\app\email\factory\builder::make();
		$email->set_subject("Account Activated");
        $email->set_to($person);
		$email->set_content(function($email)use($person){

			//build body
			$buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();
			$buffer->p(["*" => "Dear {$person->format_name()}"]);
			$buffer->p(["*" => "Your account has been activated."]);

			return $buffer->build();

		});

		return $email->send();

  	}
    //--------------------------------------------------------------------------------

	/**
	 * @param $booking
	 * @param array $options
	 * @return \com\db\row|\com\db\table|\db_email|false
	 */
	public static function send_list_property_to_admin($person, $options = []) {

		$options = array_merge([
		    "lis_type" => false,
		    "asking_price" => false,
		    "address_str" => false,
		    "description" => false,
		    "acl_code" => false,
		], $options);

		//send email
        $email = \LiquidedgeApp\Octoapp\app\app\email\factory\builder::make();
		$email->set_subject("Contact Request");
		$email->add_person(\core::$app->get_instance()->get_option("email.admin"));

		$email->set_content(function()use($person, $options){

			//build body
			$buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();
			$buffer->p(["*" => "You have received a request to list a new property"]);

			$ul = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->ul();
			$ul->add_li("Firstname:", $person->per_firstname);
			$ul->add_li("Surname:", $person->per_lastname);
			$ul->add_li("Email:", $person->per_email);
			$ul->add_li("Cell Nr:", $person->per_cellnr);
			$ul->add_li("Person Type:", \LiquidedgeApp\Octoapp\app\app\solid\solid::get_instance_acl_role($options["acl_code"])->get_acl_name());
			$ul->add_li("Listing Type:", LISTING_TYPE_ARRAY[$options["lis_type"]]);
			$ul->add_li("Asking Price:", \LiquidedgeApp\Octoapp\app\app\num\num::currency($options["asking_price"]));
			$ul->add_li("Address:", nl2br($options["address_str"]));
			$ul->add_li("Description:", $options["description"]);

			return $buffer->build();
		});

		return $email->send();

	}
    //--------------------------------------------------------------------------------
}