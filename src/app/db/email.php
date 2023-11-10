<?php

namespace LiquidedgeApp\Octoapp\app\db;

/**
 * Database Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class email extends \com\email\db\email {

	use \LiquidedgeApp\Octoapp\app\app\db\tra\table;

    //--------------------------------------------------------------------------------
    // properties
    //--------------------------------------------------------------------------------

	public $name = "email";
	public $key = "ema_id";
	public $display = "ema_subject";
	public $parent = "ema_ref_email";
	public $display_name = "email";
	public $string = "ema_tracking_id";

	public $field_arr = array( // FIELDNAME => DISPLAY[0] DEFAULT[1] TYPE[2] REFERENCE[3]
		"ema_id"				=> array("id"						, "null", DB_INT),

		// parts
		"ema_subject"			=> array("subject"					, ""	, DB_STRING),
		"ema_body"				=> array("body"						, ""	, DB_HTML),

		// properties
		"ema_status"			=> array("status"					, 0		, DB_ENUM),
		"ema_direction"			=> array("direction"				, 0		, DB_ENUM),
		"ema_ref_person"		=> array("user"						, "null", DB_REFERENCE,	"person"),
		"ema_priority"			=> array("priority"					, 5		, DB_INT),
		"ema_importance"		=> array("importance"				, 0		, DB_ENUM),
		"ema_error_message"		=> array("error message"			, ""	, DB_STRING),
		"ema_read_receipt_email"=> array("email read receipt to"	, ""	, DB_STRING),
		"ema_retry_count"		=> array("retry count"				, 0		, DB_INT),
		"ema_message_id"		=> array("message id"				, ""	, DB_STRING),

		// dates
		"ema_date_added"		=> array("date added"				, "null", DB_DATETIME),
		"ema_date_sent"			=> array("date sent"				, "null", DB_DATETIME),
		"ema_date_schedule"		=> array("date scheduled"			, "null", DB_DATETIME),

		// settings
		"ema_connection"		=> array("connection"				, ""	, DB_STRING),
		"ema_append_newlines"	=> array("append newlines"			, 0		, DB_BOOL),

		// links
		"ema_ref_email"			=> array("related email"			, "null", DB_REFERENCE,	"email"),
		"ema_ref_email_start"	=> array("start email"				, "null", DB_REFERENCE,	"email"),

		// tracking
		"ema_tracking_id"		=> array("tracking id"				, ""	, DB_STRING),
		"ema_identifier"		=> array("identifier"				, ""	, DB_STRING),
		"ema_date_opened"		=> array("opened on"				, "null", DB_DATETIME),
	);
	//--------------------------------------------------------------------------------
	public $ema_status = [
		0 => "-- Not Selected --",
		1 => "Outbox",
		2 => "Sent",
		3 => "Error",
		4 => "Inbox",
		5 => "Draft",
		6 => "Loading",
		7 => "Force",
	];
	public $ema_direction = [
		0 => "-- Not Selected --",
		1 => "In",
		2 => "Out",
	];
	public $ema_importance = [
		0 => "-- Not Selected --",
		5 => "Low",
		3 => "Normal",
		1 => "High",
	];
	//--------------------------------------------------------------------------------
	// functions
 	//--------------------------------------------------------------------------------
 	//--------------------------------------------------------------------------------
}