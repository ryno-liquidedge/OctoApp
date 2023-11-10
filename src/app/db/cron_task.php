<?php

namespace LiquidedgeApp\Octoapp\app\db;

/**
 * Database Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class cron_task extends \com\db\table {

    use \LiquidedgeApp\Octoapp\app\app\db\tra\table;

	//--------------------------------------------------------------------------------
	// properties
	//--------------------------------------------------------------------------------
	public $name = "cron_task";
	public $key = "cro_id";
	public $display = "cro_name";
	public $string = "cro_class";
	public $audit = false;
	public $trace = false;

	public $display_name = "cron task";

	public $field_arr = array( // FIELDNAME => DISPLAY[0] DEFAULT[1] TYPE[2] REFERENCE[3]
		"cro_id"				=> array("id"					, "null", DB_KEY),
		"cro_name"				=> array("name"					, ""	, DB_STRING),
		"cro_class"				=> array("class"				, ""	, DB_STRING),
		"cro_date_last_run"		=> array("last run date"		, "null", DB_DATETIME),
		"cro_date_next_run"		=> array("next run date"		, "null", DB_DATETIME),
		"cro_trigger"			=> array("trigger"				, ""	, DB_STRING),
		"cro_is_active"			=> array("is active"			, 0		, DB_BOOL),
	);
	//--------------------------------------------------------------------------------
	// events
	//--------------------------------------------------------------------------------
	public function on_auth(&$company, $user, $role) {
		return in_array($role, ["DEV", "ADMIN"]);
	}
    //--------------------------------------------------------------------------------
	public function on_auth_use(&$company, $user, $role) {
		return $this->auth_for($company, $user, $role);
	}
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function create_cron_task_item($cron_task, $options = []) {
		// options
		$options = array_merge([
		], $options);

		// params
		$cron_task = $this->splat($cron_task);

		// create cron task item
		$cron_task_item = \core::$db->cron_task_item->get_fromdefault();
		$cron_task_item->cri_ref_cron_task = $cron_task->id;
		$cron_task_item->cri_date_start = \LiquidedgeApp\Octoapp\app\app\date\date::strtodatetime("now");
		$cron_task_item->cri_status = 1; // running
		$cron_task_item->cri_pid = getmypid();

		$cron_task_item->apply_options($options);
		$cron_task_item->save();

		// done
		return $cron_task_item;
	}
	//--------------------------------------------------------------------------------
	public function is_due($cron_task) {
		return \LiquidedgeApp\Octoapp\app\app\date\date::compare($cron_task->cro_date_next_run, "<=", "now", ["use_time" => true]);
	}
	//--------------------------------------------------------------------------------
	public function is_running($cron_task) {
		// params
		$cron_task = $this->splat($cron_task);

		// done
		return (bool)$cron_task->get_reference_count("cri_ref_cron_task", "cri_status = 1");
	}
	//--------------------------------------------------------------------------------
	public function get_reference_count($obj, $field, $SQL_where = false) {
		// params
		$obj = $this->splat($obj);

		// sql:where
		if ($SQL_where) $SQL_where = " AND ({$SQL_where})";

		// count reference items
		return \core::dbt("cron_task_item")->count("{$field} = '{$obj->id}'{$SQL_where}");
	}
	//--------------------------------------------------------------------------------
	public function activate($cron_task) {
		// params
		$cron_task = $this->splat($cron_task);

		// update
		$cron_task->cro_is_active = 1;
		$cron_task->update();
	}
	//--------------------------------------------------------------------------------
	public function deactivate($cron_task) {
		// params
		$cron_task = $this->splat($cron_task);

		// update
		$cron_task->cro_is_active = 0;
		$cron_task->update();
	}
	//--------------------------------------------------------------------------------
	public function is_active($cron_task) {
		// params
		$cron_task = $this->splat($cron_task);

		// done
		return $cron_task->cro_is_active;
	}
	//--------------------------------------------------------------------------------
	/**
	 * @param type $cron_task
	 * @return \db_cron_task_item
	 */
	public function get_running_item($cron_task) {
		// params
		$cron_task = $this->splat($cron_task);

		// done
		return \core::$db->cron_task_item->get_fromdb("cri_ref_cron_task = {$cron_task->id} AND cri_status = 1 ORDER BY cri_date_start DESC");
	}
	//--------------------------------------------------------------------------------
    /**
     * @param type $cron_task
     * @return \db_cron_task_item
     */
	public function get_latest_cron_task_item($cron_task) {
		// params
		$cron_task = $this->splat($cron_task);

		// done
		return \core::$db->cron_task_item->get_fromdb("cri_ref_cron_task = {$cron_task->id} AND cri_status = 2 ORDER BY cri_date_start DESC");
	}
	//--------------------------------------------------------------------------------
	public function run($cron_task) {
		// params
		$cron_task = $this->splat($cron_task);

		$task = \com\cron\helper::make($cron_task->cro_class);
		if (!$task) return false;

		// run the cron
        switch (PHP_OS){
            case "Linux":

                $class_name = $task->get_class();
                $cronfile = \core::$folders->get_app()."/cron/trigger.php";

                if(!file_exists($cronfile)) {
                    return \com\error::create("Cron File $cronfile is missing.", ["fatal" => true]);
                }

                $ssh_name = str_replace("/usr/www/users/", "", $cronfile);
                $ssh_name_parts = explode("/", $ssh_name);
                $ssh_name = reset($ssh_name_parts);

                $command = str_replace("/usr/www/users/{$ssh_name}/", "/usr/home/{$ssh_name}/public_html/", "/usr/bin/php-wrapper {$cronfile} run={$class_name}  >/dev/null 2>/dev/null &");
                $result = shell_exec($command);
                break;

            default:
                $result = \com\os::php(\core::$folders->get_app()."/cron/cron.php --run={$task->get_class()} --force=1");
                break;
        }

		// done
		return $result;
	}
	//--------------------------------------------------------------------------------
}