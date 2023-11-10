<?php

namespace LiquidedgeApp\Octoapp\app\db;

/**
 * Database Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class session extends \com\session\db\session {

	use \LiquidedgeApp\Octoapp\app\app\db\tra\table;

    //--------------------------------------------------------------------------------
    // properties
    //--------------------------------------------------------------------------------

 	//--------------------------------------------------------------------------------
	// functions
 	//--------------------------------------------------------------------------------
	public static function get_session_count($context) {

        if(!$context) return false;

        $context = new \com\context($context);
        $context->filter_type("today", "sql_where", "DATE(ses_date_added) = CURDATE()");
        $context->filter_type("today", "groupby", "DATE(ses_date_added)");

        $context->filter_type("month", "sql_where", "month(ses_date_added) = '".\LiquidedgeApp\Octoapp\app\app\date\date::strtodate("today", "m")."'");
        $context->filter_type("month", "groupby", "YEAR(ses_date_added),MONTH(ses_date_added)");

        $sql = \com\db\sql\select::make();
        $sql->select("COUNT(ses_id)");
        $sql->from("session");
        $sql->from("LEFT JOIN session_token ON (set_ref_session = ses_id)");
        $sql->or_where("set_id IS NULL");
        $sql->or_where(\core::db()->getsql_in([ACL_CODE_STORE_MANAGER, ACL_CODE_CLIENT, ACL_CODE_STAFF_MEMBER], "set_role"));
        $sql->or_where("ses_ref_person IS NULL");
        $sql->and_where($context->sql_where);
        $sql->groupby($context->groupby);

        return \core::db()->selectsingle($sql->build());
	}
 	//--------------------------------------------------------------------------------
}