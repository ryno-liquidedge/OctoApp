<?php

namespace app\data\model;

/**
 * @package app\data\model
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class email extends \com\core\model\email {
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct($options) {
		// options
		$options = array_merge([
			"allow_system_token" => false,
		], $options);

		// init
		$this->name = "email";
		$this->resource = "email";
		$this->key_field = "ema_id";
		$this->name_field = "ema_subject";
		$this->parent_field = "ema_ref_email";
		$this->code_field = "ema_tracking_id";

		// fields
		$this->fields = \com\data\fields::make()
			->add("ema_id", "id", \com\data\type\tkey::make())
			->add("ema_subject", "subject", \com\data\type\tstring::make())
			->add("ema_body", "body", \com\data\type\traw::make(), ["allow_tag_arr" => ["a"]])
			->add("ema_status", "status", \com\data\type\tlist::make())
			->add("ema_direction", "direction", \com\data\type\tlist::make())
			->add_reference("ema_ref_person", "user", "person")
			->add("ema_priority", "priority", \com\data\type\tinteger::make())
			->add("ema_importance", "importance", \com\data\type\tlist::make())
			->add("ema_error_message", "error message", \com\data\type\tstring::make())
			->add("ema_read_receipt_email", "email read receipt to", \com\data\type\tstring::make())
			->add("ema_retry_count", "retry count", \com\data\type\tinteger::make())
			->add("ema_date_added", "date added", \com\data\type\tdatetime::make())
			->add("ema_date_sent", "date sent", \com\data\type\tdatetime::make())
			->add("ema_date_schedule", "date scheduled", \com\data\type\tdatetime::make())
			->add("ema_connection", "connection", \com\data\type\tstring::make())
			->add("ema_append_newlines", "append newlines", \com\data\type\tboolean::make())
			->add("ema_message_id", "message id", \com\data\type\tstring::make())
			->add_reference("ema_ref_email", "related email", "email")
			->add_reference("ema_ref_email_start", "start email", "email")
		;

		// storage
		$this->storage = \com\data\storage\dbt::with($this, \core::dbt("email"));
	}
	//--------------------------------------------------------------------------------
}