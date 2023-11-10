<?php

namespace LiquidedgeApp\Octoapp\app\db;

/**
 * Database Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class document extends \com\db\table {

    use \LiquidedgeApp\Octoapp\app\app\db\tra\table;

    //--------------------------------------------------------------------------------
    // properties
    //--------------------------------------------------------------------------------
    public $name = "document";
    public $key = "doc_id";
    public $display = "doc_title";
    
    public $display_name = "document";
    
    public $field_arr = array(// FIELDNAME => DISPLAY[0] DEFAULT[1] TYPE[2] REFERENCE[3]
        // identification
        "doc_id"                        => array("id",                  "null",     DB_INT),
        "doc_title"                    	=> array("title",             	"",      	DB_STRING),
        "doc_ref_file_item"           	=> array("file item",          	"null",   	DB_REFERENCE, "file_item"),
        "doc_ref_person"           		=> array("person",          	"null",   	DB_REFERENCE, "person"),
        "doc_date_created"           	=> array("date created",       	"null",   	DB_DATETIME),
        "doc_type"           			=> array("type",       			0,   		DB_ENUM),
        "doc_order"           			=> array("order",       		0,   		DB_INT),
    );
    //--------------------------------------------------------------------------------
	public $doc_type = [
		0 => "-- Not Selected --",
	] + ACCESS_TYPE_ARR;
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
    public function on_insert(&$obj) {
        parent::on_insert($obj);
        $obj->doc_date_created = \LiquidedgeApp\Octoapp\app\app\date\date::strtodatetime();
    }
    //--------------------------------------------------------------------------------
	public function on_delete(&$obj) {
		$document_role_arr = \core::dbt("document_acl_role")->get_fromdb("dar_ref_document = '{$obj->id}'", ["multiple" => true]);
		foreach ($document_role_arr as $document_role) $document_role->delete();
	}
	//--------------------------------------------------------------------------------
	public function on_update_complete(&$obj, &$current_obj) {

    	if($obj->doc_ref_file_item != $current_obj->doc_ref_file_item){
    		$current_obj->file_item->delete();
		}
	}
	//--------------------------------------------------------------------------------
	public function has_role($document_id, $acl_role_id) {
		// params
		$document_id = $this->splatid($document_id);
		$acl_role_id = \core::$db->acl_role->splatid($acl_role_id);

		// return result
		if (!$acl_role_id) return false;
		return \core::$db->document_acl_role->exists("dar_ref_document = '$document_id' AND dar_ref_acl_role = '$acl_role_id'");
	}
	//--------------------------------------------------------------------------------
	public function get_role_list($document, $options = []) {

		$sql = \LiquidedgeApp\Octoapp\app\app\db\sql\select::make();
		$sql->select("acl_name");
		$sql->select("acl_code");

		$sql->from("acl_role");
		$sql->from("LEFT JOIN document_acl_role ON (dar_ref_acl_role = acl_id)");

		$sql->and_where("dar_ref_document = ".dbvalue($document->id));

		$sql->extract_options($options);

		// return role list
		return \com\db::selectlist($sql->build(), "acl_code", "acl_name");
	}
	//--------------------------------------------------------------------------------
	public function assign_roles($document, $dar_ref_acl_role_arr = []) {

    	//add roles
		foreach ($dar_ref_acl_role_arr as $acl_code){
        	$document->add_role($acl_code);
		}

        //remove roles
		$sql = \LiquidedgeApp\Octoapp\app\app\db\sql\select::make();
		$sql->select("document_acl_role.*");
		$sql->from("document_acl_role");
		$sql->from("LEFT JOIN acl_role ON (dar_ref_acl_role = acl_id)");
		$sql->and_where("dar_ref_document = {$document->id}");
		$sql->and_where(\core::db()->getsql_in($dar_ref_acl_role_arr, "acl_code", false, true));
		$document_acl_role_arr = \core::dbt("document_acl_role")->get_fromsql($sql->build(), ["multiple" => true]);
		foreach ($document_acl_role_arr as $document_acl_role) $document_acl_role->delete();

	}
	//--------------------------------------------------------------------------------
	public function add_role($document_id, $acl_role) {
		// params
		$document_id = $this->splatid($document_id);
		$acl_role = \core::$db->acl_role->splat($acl_role);

		// add role
		$document_acl_role = \core::$db->document_acl_role->get_fromdefault();
		$document_acl_role->dar_ref_document = $document_id;
		$document_acl_role->dar_ref_acl_role = $acl_role->id;
		$document_acl_role->insert();
	}
	//--------------------------------------------------------------------------------
	public function remove_role($document_id, $acl_role_id) {
		// params
		$document_id = $this->splatid($document_id);
		$acl_role_id = \core::$db->acl_role->splatid($acl_role_id);

		// fetch role
		$document_acl_role = \core::$db->document_acl_role->get_fromdb("dar_ref_document = '$document_id' AND dar_ref_acl_role = '$acl_role_id'");
		if (!$document_acl_role) return false;

		// delete role
		$document_acl_role->delete();
	}
	//--------------------------------------------------------------------------------
}