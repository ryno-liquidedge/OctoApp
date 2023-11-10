<?php

namespace action\developer\cron_task;

/**
 * Action Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class vlist implements \com\router\int\action {
    /**
     * @var \com\context
     */
    protected $context;

	//--------------------------------------------------------------------------------
	use \com\router\tra\action;
	//--------------------------------------------------------------------------------
	public function auth() {
		return $this->token->check("dev");
	}
	//--------------------------------------------------------------------------------
	public function run() {
		// params
		$stream = $this->request->get("stream", \com\data::TYPE_BOOL, ["get" => true]);

		// init
		message(false);
		$this->context = new \com\context();
		$this->context->filter_default("header", "System Crons");

		$this->context->filter_type("active", "sql_where", "cro_is_active = 1");
		$this->context->filter_type("active", "header", "Active crons");

		$this->context->filter_type("inactive", "sql_where", "cro_is_active = 0");
		$this->context->filter_type("inactive", "header", "Inactive crons");

		// list
		$list = \com\ui::make()->table(false, ["sortfield" => 3, "sortorder" => 1]);
		$list->key = "cro_id";
		$list->enable_prepare = true;
		$list->quickfind_field = "cro_name";
		$list->set_sql($this->get_sql());

		// sql

		// fields
		$list->add_field("Name", "cro_name");
		$list->add_field("Status", "cri_status", ["lookup" => true, "function" => function($content, $item_index, $field_index, $list) {
			// init
			$item = $list->item_arr[$item_index];

			// determine inactive reason
			$status_reason = false;
			$task = \com\cron\helper::make($item["cro_class"]);
			if (!$task) return $content;
			if (!$task->is_active()) $status_reason = " (Database)";
			elseif (!$task->is_enabled()) $status_reason = " (Hardcoded)";
			elseif (!$task->is_environment()) $status_reason = " (Environment)";

			// no current status
			if ($status_reason) $content = "Inactive{$status_reason}";

			// done
			return $content;
		}]);
		$list->add_field("Last duration", "cri_duration", ["format" => DB_SECONDS]);
		$list->add_field("Last run", "cro_date_last_run", ["format" => DB_DATETIME]);
		$list->add_field("Next run", "cro_date_next_run", ["format" => DB_DATETIME]);
		$list->add_field("Trigger", "cro_trigger");
		$list->add_field("Class", "cro_class");
		$list->add_field("PID", "cri_pid");
		$list->add_field("ID", "cro_id", ["format" => DB_KEY]);

		// actions
		$list->add_action_manage("?c=developer.cron_task_item/vlist&cro_id=%cro_id%");
		$list->add_action("run", "{$this->request->get_panel()}.requestRefresh('?c=developer.cron_task/xrun/%cro_id%', { confirm: true });", "play", ["filter" => "cri_status IN (2,3,4,5) OR cri_status IS NULL"]);
		$list->add_action("toggle active status", "{$this->request->get_panel()}.requestRefresh('?c=developer.cron_task/xtoggle/%cro_id%');", "fa-check");
		$list->add_action("kill", "{$this->request->get_panel()}.requestRefresh('?c=developer.cron_task/xkill/%cri_id%', { confirm: true });", "fa-times", ["filter" => "cri_status IN (1)"]);

		// legend
		$list->add_legend("blue", "Running", "cri_status = 1");
		$list->add_legend("grey", "Inactive", "cro_is_active = 0");
		$list->add_legend("red", "Issue", "cri_status IN (4,5)");

		// stream
		if ($stream) {
			$list->stream_buffer(["filename" => "cron-list-export.csv"]);
			return "stream";
		}

		// html
		$html = \com\ui::make()->html();
		$html->header(2, $this->context->header);
		$html->button("refresh");
		$html->button("export", "document.location = '{$this->request->get_url()}&stream=1';");
		$html->button("trigger", "{$this->request->get_panel()}.requestRefresh('?c=developer.cron_task/xtrigger');");

		$command = \app\os::get_linux_cron_command();
		if($command) $html->button("copy cron command", "core.util.copy_text_to_clipboard('{$command}')");
		$html->display($list);
	}
	//--------------------------------------------------------------------------------
    public function get_sql() {

	    $sql_nolock = \core::db()->getsql_nolock();
	    $sql = \com\db\sql\select::make();

	    $sql->select("cro_id");
	    $sql->select("cro_name");
	    $sql->select("cro_class");
	    $sql->select("cro_date_last_run");
	    $sql->select("cro_date_next_run");
	    $sql->select("cro_trigger");
	    $sql->select("cro_is_active");
	    $sql->select("cri_id");
	    $sql->select("cri_status");
	    $sql->select("cri_pid");
	    if(\core::$app->get_instance()->get_db_type() == "sqlsrv"){
			$sql->select("(CASE WHEN cri_date_end IS NULL THEN DATEDIFF(SECOND, cri_date_start, GETDATE()) ELSE cri_duration END) AS cri_duration");
		}else{
			$sql->select("(CASE WHEN cri_date_end IS NULL THEN TIME_TO_SEC(TIMEDIFF(NOW(), cri_date_start)) ELSE cri_duration END) AS cri_duration");
		}

	    $sql->from("cron_task{$sql_nolock}");

	    if(\core::$app->get_instance()->get_db_type() == "sqlsrv"){
			$sql->from("OUTER APPLY ( SELECT TOP 1 cri_id, cri_status, cri_pid, cri_duration, cri_date_start, cri_date_end FROM cron_task_item WHERE cri_ref_cron_task = cro_id ORDER BY cri_date_start DESC) AS z");
		}else{
			$sql->from("LEFT JOIN cron_task_item{$sql_nolock} ON  ( cron_task_item.cri_id = (SELECT cri_id FROM cron_task_item WHERE cri_ref_cron_task = cro_id ORDER BY cri_date_start DESC LIMIT 1))");
		}

	    $sql->and_where($this->context->sql_where);

	    return $sql;

    }
	//--------------------------------------------------------------------------------
}