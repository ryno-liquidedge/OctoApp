<?php

namespace LiquidedgeApp\Octoapp\app\db;

/**
 * Database Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class request extends \com\db\table {

	use \LiquidedgeApp\Octoapp\app\app\db\tra\table;

	//--------------------------------------------------------------------------------
	// properties
	//--------------------------------------------------------------------------------
	public $name = "request";
	public $key = "req_id";
	public $display = "req_date";
	public $parent = "req_ref_person";
	public $string = "req_code";

	public $display_name = "request";

	public $field_arr = array( // FIELDNAME => DISPLAY[0] DEFAULT[1] TYPE[2] REFERENCE[3]
		"req_id"				=> array("database id"		, "null"	, DB_KEY),
		"req_date"				=> array("date"				, "null"	, DB_DATETIME),
		"req_code"				=> array("code"				, ""		, DB_STRING),
		"req_data"				=> array("data"				, ""		, DB_TEXT),
		"req_json"				=> array("json data"    	, ""		, DB_TEXT),
		"req_expire_date"		=> array("expiry date"		, "null"	, DB_DATETIME),
		"req_ref_reference"		=> array("reference"		, 0			, DB_INT),
		"req_type"				=> array("type"				, 0			, DB_ENUM),
		"req_ref_person"		=> array("requestee"		, "null"	, DB_REFERENCE,		"person"),
		"req_ref_person_user"	=> array("user"				, "null"	, DB_REFERENCE,		"person"),
		"req_timeout"			=> array("timeout"			, 0			, DB_BOOL),
		"req_retry_count"		=> array("timeout"			, 0			, DB_INT),
	);
	//--------------------------------------------------------------------------------
	public $req_type = array(
		0 => "-- Not Selected --",
		1 => "Forgot password",
		2 => "Register",
		3 => "Login OTP",
		7 => "Username Changed",
	);
	//--------------------------------------------------------------------------------
	// events
	//--------------------------------------------------------------------------------
	public function on_auth(&$request, $user, $role) {
		return true;
	}
    //--------------------------------------------------------------------------------
	public function on_auth_use(&$request, $user, $role) {
		return true;
	}
    //--------------------------------------------------------------------------------
	public function on_insert(&$request) {
		// user
		if ($request->is_empty("req_ref_person_user")) $request->req_ref_person_user = \com\user::$user_id;

		// date
		if ($request->is_empty("req_date")) $request->req_date = \LiquidedgeApp\Octoapp\app\app\date\date::strtodatetime();

		// expire date
		if ($request->is_empty("req_expire_date")) {
			switch ($request->req_type) {

				case 7 :
                case 1 : $request->req_expire_date = \LiquidedgeApp\Octoapp\app\app\date\date::strtodatetime("now +2 hour"); break;

                case 3 : $request->req_expire_date = \LiquidedgeApp\Octoapp\app\app\date\date::strtodatetime("now +5 minutes"); break;
                case 4 : $request->req_expire_date = \LiquidedgeApp\Octoapp\app\app\date\date::strtodate("today + 1 year"); break;

                case 102 :
                case 5 : $request->req_expire_date = \LiquidedgeApp\Octoapp\app\app\date\date::strtodate("now + 1 month"); break;
            }

            // code
            if ($request->is_empty("req_code")) {
                // auto generate code
                $request->req_code = \com\str::get_random_alpha(128);
                while ($this->get_fromdb("req_code = '$request->req_code'")) {
                    $request->req_code = \com\str::get_random_alpha(128);
                }
            }
            else {
                // check for duplicate
                $current_request = $this->get_fromdb("req_code = '$request->req_code'");
                if ($current_request) return false;
            }
        }
    }
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function get_fromdb($mixed, $options = []) {
		// options
  		$options = array_merge(array(
			"multiple" => false,
  		), $options);

		// delete all expired requests
		if (!$options["multiple"]) {
			$SQL_date = \LiquidedgeApp\Octoapp\app\app\date\date::strtodatetime();
			$request_arr = $this->get_fromdb("req_expire_date < '$SQL_date'", ["multiple" => true]);
			foreach ($request_arr as $request_item) {
				$request_item->delete();
			}
		}

		// run parent
		return parent::get_fromdb($mixed, $options);
	}
	//--------------------------------------------------------------------------------

	/**
	 * @param $req_type
	 * @param $req_ref_reference
	 * @param array $options
	 * @return \com\db\row|\com\db\row[]|\com\db\table|\com\db\table[]|false|db_request
	 */
	public static function create_request($req_type, $req_ref_reference, $options = []) {
		//create request entry
        $request = \core::dbt("request")->find([
            ".req_type" => $req_type,
            ".req_ref_reference" => $req_ref_reference,
            "create" => true,
        ]);
        $request->save();

        return $request;
	}
	//--------------------------------------------------------------------------------
}