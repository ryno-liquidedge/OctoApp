<?php

namespace LiquidedgeApp\Octoapp\app\db;

/**
 * Database Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class person extends \com\core\db\person {

	use \LiquidedgeApp\Octoapp\app\app\db\tra\table;

	//--------------------------------------------------------------------------------
	// properties
	//--------------------------------------------------------------------------------
	public $name = "person";
	public $key = "per_id";
	public $display = "per_name";
	public $string = "per_email";

	public $display_name = "person";
	public $property_table = "person_property";

	public $field_arr = array( // FIELDNAME => DISPLAY[0] DEFAULT[1] TYPE[2] REFERENCE[3]
	 	// identification
		"per_id" 						=> array("database id"				, "null", DB_KEY),
		"per_name" 						=> array("name"						, ""	, DB_STRING),
		"per_title" 					=> array("title"					, 0		, DB_ENUM),
		"per_initial" 					=> array("initials"					, "" 	, DB_STRING),
		"per_firstname" 				=> array("first names"				, ""	, DB_STRING),
		"per_lastname" 					=> array("surname"					, ""	, DB_STRING),
		"per_preferredname" 			=> array("preferred name"			, ""	, DB_STRING),
		"per_idnr" 						=> array("identity number"			, ""	, DB_STRING),
		"per_idnr_clean" 				=> array("clean identity number"	, ""	, DB_STRING),
		"per_ref_idnr_country" 			=> array("country of id issue"		, "null", DB_REFERENCE, "country"),
		"per_passportnr" 				=> array("passport number"			, ""	, DB_STRING),
		"per_taxnr" 					=> array("tax number"				, ""	, DB_STRING),
		"per_vatnr" 					=> array("vat number"				, ""	, DB_STRING),
		"per_estatenr" 					=> array("estate number"			, ""	, DB_STRING),
		"per_findstring" 				=> array("find string"				, ""	, DB_STRING),
		"per_tradingname" 				=> array("trading name"				, ""	, DB_STRING),
		"per_maidenname" 				=> array("maiden name"				, "" 	, DB_STRING),
		"per_birthday" 					=> array("birthday"					, "null", DB_DATE),
		"per_gender" 					=> array("gender"					, 0 	, DB_ENUM),
		"per_jobtitle" 					=> array("job title"				, ""	, DB_STRING),
		"per_ref_language" 				=> array("language"					, "null", DB_REFERENCE, "language"),
		"per_is_deceased" 				=> array("is deceased"				, 0		, DB_BOOL),
		"per_trust_type" 				=> array("trust type"				, 0		, DB_ENUM),
		"per_salutation"				=> array("salutation"				, 0 	, DB_ENUM),

		// historic fields
		"per_firstname_previous" 		=> array("previous first names"		, ""	, DB_STRING),
		"per_lastname_previous" 		=> array("previous surname"			, ""	, DB_STRING),
		"per_preferredname_previous" 	=> array("previous preferred name"	, ""	, DB_STRING),

		// contact
		"per_telnr_home" 				=> array("home number"				, "  "	, DB_TELNR),
		"per_telnr_work"				=> array("work number"				, "  "	, DB_TELNR),
		"per_cellnr"					=> array("cell number"				, "  "	, DB_TELNR),
		"per_faxnr"						=> array("fax number"				, "  "	, DB_TELNR),
		"per_email" 					=> array("email"					, ""	, DB_EMAIL),
		"per_email_work" 				=> array("work email"				, ""	, DB_EMAIL),
		"per_website"	 				=> array("website"					, ""	, DB_STRING),

		// account
		"per_username" 					=> array("username"					, ""	, DB_STRING),
		"per_password" 					=> array("password"					, "null", DB_STRING),
		"per_is_active" 				=> array("is active"				, 0		, DB_BOOL),
		"per_terms_accepted" 		    => array("terms accepted"		    , 0		, DB_BOOL),
		"per_date_login"				=> array("login date"				, "null", DB_DATE),
		"per_date_login_previous"		=> array("previous login date"		, "null", DB_DATE),
		"per_retry_count"				=> array("login retry count"		, 0		, DB_INT),
		"per_retry_date"				=> array("last login try date"		, "null", DB_DATE),
		"per_ref_person_type"			=> array("person type"				, "null", DB_REFERENCE		, "person_type"),
		"per_ref_person_created"		=> array("person created"			, "null", DB_REFERENCE		, "person"),
		"per_enable_concurrent_login" 	=> array("enable concurrent login"	, 0		, DB_BOOL),

		"per_is_terms_checked" 			=> array("has checked terms"		, 0		, DB_BOOL),

		"per_remote_id"				    => array("remote id"				, 0 	, DB_INT),
		"per_old_id"				    => array("old db id"				, 0 	, DB_INT),
        "per_date_created"		        => array("date created"		        , "null", DB_DATETIME),
	);
	//--------------------------------------------------------------------------------
	public $per_salutation = [
		0 => "-- Not Selected --",
		1 => "Preferred Name Surname",
		2 => "Title Preferred Name Surname",
		3 => "Title Initials Surname",
		4 => "Title Surname",
		5 => "Title Preferred name",
		6 => "First name",
		7 => "Preferred name",
	];
	public $per_trust_type = [
		0 => "-- Not Selected --",
		1 => "Inter vivos",
		2 => "Testamentary"
	];
	public $per_gender = [
		0 => "-- Not Selected --",
		1 => "Male",
		2 => "Female",
	];
	public $per_title = [
		0 => "-- Not Selected --",
		1 => "Mr",
		2 => "Ms",
		8 => "Mrs",
		3 => "Dr",
		4 => "Prof",
		5 => "Adv",
		6 => "Rev",
		7 => "Ds",
		9 => "Col",
		10 => "Gen",
		11 => "Judge",
	];
	public $per_title_afr = [
		0 => "-- Not Selected --",
		1 => "Mnr",
		2 => "Me",
		8 => "Mev",
		13 => "Mej",
		3 => "Dr",
		4 => "Prof",
		5 => "Adv",
		7 => "Ds",
		9 => "Kol",
		10 => "Genl",
		11 => "Regter",
		6 => "Rev",
	];

 	//--------------------------------------------------------------------------------
	// functions
 	//--------------------------------------------------------------------------------
    public function on_auth(&$obj, $user, $role) {
        return \core::$app->get_token()->check('users');
    }

    //--------------------------------------------------------------------------------
    public function on_auth_use(&$obj, $user, $role) {
        return \core::$app->get_token()->check('users');
    }
    //--------------------------------------------------------------------------------
    public function on_insert(&$person) {
        parent::on_insert($person);
        $person->per_date_created = \LiquidedgeApp\Octoapp\app\app\date\date::strtodatetime();
    }
    //--------------------------------------------------------------------------------
	public function on_save(&$person) {

    	if($person->is_empty("per_username") && !$person->is_empty("per_email")){
    		$person->per_username = $person->per_email;
		}

    	if(!$person->person_type){
    		$person->person_type = \core::dbt("person_type")->splat("INDIVIDUAL");
    		$person->per_ref_person_type = $person->person_type->pty_id;
		}

		parent::on_save($person);
	}
	//--------------------------------------------------------------------------------
    public static function get_person_list($options = []) {

        $options = array_merge([
            "role_arr" => []
        ], $options);

        $sql = \LiquidedgeApp\Octoapp\app\app\db\sql\select::make();
        $sql->select("per_id");
        $sql->select("per_name");

        $sql->from("person");
        $sql->from("LEFT JOIN person_role ON (person.per_id = person_role.pel_ref_person)");
        $sql->from("LEFT JOIN acl_role ON (person_role.pel_ref_acl_role = acl_role.acl_id)");

        $sql->extract_options($options);

        if($options["role_arr"]){
            $sql->and_where(\core::db()->getsql_in($options["role_arr"], "acl_code"));
        }

        return \core::db()->selectlist($sql->build(), "per_id", "per_name");

    }
    //--------------------------------------------------------------------------------
    /**
     * checks to see if the email address is unique
     * @param $obj db_person
     * @return bool
     */
	public function is_unique($person) {

		// params
		$person = $this->splat($person);

		// sql
		$sql = \com\db\sql\select::make();
		$sql->select("per_id");
		$sql->from("person");
		$sql->and_where("per_email = ".dbvalue($person->per_email));

		// existing person
		if (!$person->is_empty("per_id")) $sql->and_where("per_id <> '$person->id'");

		// check for unique username
		return !(bool)\core::db()->selectsingle($sql->build());
	}
 	//--------------------------------------------------------------------------------
	public function format_name($person, $format = ":firstname:lastname", $options = []) {
		return parent::format_name($person, $format, $options);
	}
	//--------------------------------------------------------------------------------

	/**
	 * @param $person
	 * @param array $options
	 * @return array
	 */
	public function get_contact_number_arr($person, $options = []){

		$options = array_merge([
		    "clean" => false
		], $options);

		$result_arr = [];
		if(!$person->is_empty("per_cellnr")) $result_arr[] = $person->per_cellnr;
		if(!$person->is_empty("per_telnr_home")) $result_arr[] = $person->per_telnr_home;
		if(!$person->is_empty("per_telnr_work")) $result_arr[] = $person->per_telnr_work;

		if($options["clean"]){
			foreach ($result_arr as $index => $number){
				$number = \LiquidedgeApp\Octoapp\app\app\data\data::parse_telnr($number);
				$number = str_replace(" ", "", $number);
				$result_arr[$index] = substr($number, 0, 11);
			}
		}

		return $result_arr;
	}
    //--------------------------------------------------------------------------------
	/**
	 * @param $person
	 * @param array $options
	 * @return mixed
	 */
	public function get_contact_number($person, $options = []){
		$number_arr = $person->get_contact_number_arr($options);
		return reset($number_arr);
	}
    //--------------------------------------------------------------------------------
    public function add_person_person_link($person, $person_link, $pep_type = 0, $options = []) {

    	$options = array_merge([
            ".pep_ref_person" => $person->id,
            ".pep_ref_person_link" => $person_link->id,
            ".pep_type" => $pep_type,
            "create" => true,
        ], $options);


        $person_person = \core::dbt("person_person")->find($options);

        $person_person->save();
    }
    //--------------------------------------------------------------------------------
    public function has_person_person_link($person, $pep_type = 0, $options = []) {

        $person_person = \core::dbt("person_person")->find([
            ".pep_ref_person_link" => $person->id,
            ".pep_type" => $pep_type,
        ]);

        return $person_person;
    }
	//--------------------------------------------------------------------------------

	/**
	 * @param $person
	 * @param array $options
	 * @return array|false|mixed|db_person_person[]
	 */
    public function get_person_person_arr($person, $options = []) {

        $sql = \LiquidedgeApp\Octoapp\app\app\db\sql\select::make();
        $sql->select("person_person.*");
        $sql->from("person_person");
        $sql->and_where("pep_ref_person = {$person->per_id}");
        $sql->extract_options($options);

        return \core::dbt("person_person")->get_fromsql($sql->build(), ["multiple" => true]);

    }
    //--------------------------------------------------------------------------------

	/**
	 * @param $person
	 * @param $pfi_type
	 * @param array $options
	 * @return \com\db\row|\com\db\row[]|\com\db\table|\com\db\table[]|false|db_person_file_item
	 */
    public function get_person_file_item($person, $pfi_type, $options = []) {

        if($person->is_empty("per_id")) return false;

        return \core::dbt("person_file_item")->find([
            ".pfi_ref_person" => $person->per_id,
            ".pfi_type" => $pfi_type,
        ]);
    }
    //--------------------------------------------------------------------------------
	public function get_role_list($person, $options = []) {
		// params
		$person = $this->splat($person);

		$sql = \LiquidedgeApp\Octoapp\app\app\db\sql\select::make();
		$sql->select("acl_name");
		$sql->select("acl_code");
		$sql->from("acl_role");
		$sql->and_where(function()use($person){
			$sql = \LiquidedgeApp\Octoapp\app\app\db\sql\select::make();
			$sql->select("pel_ref_acl_role");
			$sql->from("person_role");
			$sql->and_where("pel_ref_person = ".dbvalue($person->id));
			return "acl_id IN ({$sql->build()})";
		});
		$sql->extract_options($options);

		// return role list
		return \com\db::selectlist($sql->build(), "acl_code", "acl_name");
	}
    //--------------------------------------------------------------------------------

	/**
	 * @param $person
	 * @param $pfi_type
	 * @param $file_item
	 * @param array $options
	 * @return \com\db\row|\com\db\row[]|\com\db\table|\com\db\table[]|false|db_person_file_item
	 */
    public function save_person_file_item($person, $pfi_type, $file_item, $options = []) {

        $person_file_item = \core::dbt("person_file_item")->find([
            ".pfi_ref_person" => $person->per_id,
            ".pfi_type" => $pfi_type,
            "create" => true,
        ]);

        $person_file_item->pfi_ref_file_item = $file_item->fil_id;
        $person_file_item->save();

        return $person_file_item;
    }
    //--------------------------------------------------------------------------------
    public function api_update($person) {

        if(!$person->per_remote_id) return;

        if(!\db_settings::get_value(SETTING_OCTOAPI_SERVICE_URL))
    		return;

        $api = \app\api\octoapi\api::make();
	    $response = $api->person()->get()->get_person_data($person->per_remote_id);

	    $parser = \app\api\octoapi\parser\person::make();
        $parser->set_data_arr($response->get_response_data_first());
        $parser->run();
    }
	//--------------------------------------------------------------------------------
	public function api_push($person, $options = []) {

    	$options = array_merge([
    	    "empty_password" => true
    	], $options);

    	if(!\db_settings::get_value(SETTING_OCTOAPI_SERVICE_URL))
    		return;

    	$api = \app\api\octoapi\api::make();
	    $response = $api->person()->post()->add_person([
	    	"firstname" => $person->per_firstname,
			"lastname" => $person->per_lastname,
			"email" => $person->per_email,
			"cellnr" => $person->per_cellnr,
            "birthday" => $person->per_birthday,

			"password" => !$options["empty_password"] ? $person->load_field("per_password") : false,
			"empty_password" => $options["empty_password"],

            "role_arr" => \app\api\octoapi\api::parse_roles_to_octo_roles(array_keys($person->get_role_list())),
		]);

	    $meta = $response->get_response_meta();
		if(isset($meta["user_details"])){
			$person->per_remote_id = $meta["user_details"]["per_id"];
			unset($person->per_password);
			$person->update();
		}
	}
	//--------------------------------------------------------------------------------
}