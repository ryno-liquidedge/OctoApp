<?php

namespace LiquidedgeApp\Octoapp\app\db;

/**
 * Database Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class document_acl_role extends \com\db\table {

    use \LiquidedgeApp\Octoapp\app\app\db\tra\table;

    //--------------------------------------------------------------------------------
    // properties
    //--------------------------------------------------------------------------------
    public $name = "document_acl_role";
    public $key = "dar_id";
    public $display = "dar_ref_document";
    
    public $display_name = "document_acl_role";
    
    public $field_arr = array(// FIELDNAME => DISPLAY[0] DEFAULT[1] TYPE[2] REFERENCE[3]
        // identification
        "dar_id"                        => array("id",                  "null",     DB_INT),
        "dar_ref_document"           	=> array("document",          	"null",   	DB_REFERENCE, "document"),
        "dar_ref_acl_role"          	=> array("role",          		"null",   	DB_REFERENCE, "acl_role"),
    );
    //--------------------------------------------------------------------------------
    // events
    //--------------------------------------------------------------------------------
	public function on_auth(&$obj, $user, $role) {
		return \core::$app->get_token()->check('users');
	}
    //--------------------------------------------------------------------------------
	public function on_auth_use(&$obj, $user, $role) {
		return \core::$app->get_token()->check('users');
	}
    //--------------------------------------------------------------------------------
}