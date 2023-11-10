<?php

namespace LiquidedgeApp\Octoapp\app\app\cron;

/**
 * @package app\cron\cron
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class email extends \com\email {

	//--------------------------------------------------------------------------------
	public static function send_outbox($options = []) {
		// options
		$options = array_merge([
			"queue_count" => false,
			"queue_nr" => false,
			"queue_order" => "ASC",
		], $options);

		// sql: date
		$sql_getdate = \core::db()->getsql_function("GETDATE");

		// sql: final
		$sql = \LiquidedgeApp\Octoapp\app\app\db\sql\select::make();
		$sql->select("ema_id");
		$sql->from("email ".\core::db()->getsql_nolock());
		$sql->and_where("ema_status = 1");
		$sql->and_where("({$sql_getdate} >= ema_date_schedule OR ema_date_schedule IS NULL)");
		if ($options["queue_count"]) {
			$sql->and_where("ema_id % {$options["queue_count"]} = {$options["queue_nr"]}");
		}
		$sql->orderby("ema_priority ASC");
		$sql->orderby("ema_date_added {$options["queue_order"]}");
		$sql = preg_replace("/\\n\\t/is", " ", $sql->build()); // get sql, remove newlines so getsql_top will work

		// sql: top
		if (\core::$app->get_instance()->get_email_process_limit()) {
			$sql = \core::db()->getsql_top($sql, \core::$app->get_instance()->get_email_process_limit());
		}

		// sql: db
		$email_id_arr = \com\db::selectsinglelist($sql);

		// send each email
		foreach ($email_id_arr as $email_id_item) {
			self::send_outbox_item($email_id_item);
		}
	}
    //--------------------------------------------------------------------------------
}
