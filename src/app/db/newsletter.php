<?php

namespace LiquidedgeApp\Octoapp\app\db;

/**
 * Database Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class newsletter extends \com\db\table {

    use \LiquidedgeApp\Octoapp\app\app\db\tra\table;

    //--------------------------------------------------------------------------------
    // properties
    //--------------------------------------------------------------------------------
    public $name = "newsletter";
    public $key = "new_id";
    public $display = "new_title";

    public $display_name = "newsletter";
    public $slug = "new_slug";


    public $field_arr = array(// FIELDNAME => DISPLAY[0] DEFAULT[1] TYPE[2] REFERENCE[3]
        // identification
        "new_id"                    => array("id"                   , "null"	, DB_INT),
        "new_title"				    => array("title"			    , ""		, DB_STRING),
        "new_subtitle"			    => array("subtitle"			    , ""		, DB_STRING),
        "new_intro"				    => array("intro"			    , ""		, DB_TEXT),
        "new_body"				    => array("body"				    , ""		, DB_HTML),
        "new_date_created"		    => array("date created"		    , "null"	, DB_DATETIME),
        "new_date_modified"		    => array("date modified"	    , "null"	, DB_DATETIME),
        "new_is_enabled"		    => array("is published"		    , 0			, DB_BOOL),
        "new_ref_person_created"    => array("person created"		, "null"	, DB_REFERENCE, "person"),
        "new_slug"			        => array("slug"			        , ""		, DB_STRING),
        "new_order"			        => array("order"			    , 0			, DB_INT),
        "new_remote_id"             => array("remote id"            , 0	        , DB_INT),
        "new_old_id"             	=> array("old db id"            , 0	        , DB_INT),
    );
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
		
		if($obj->is_empty("new_date_created")) $obj->new_date_created = \LiquidedgeApp\Octoapp\app\app\date\date::strtodatetime();
		$obj->new_slug = $obj->build_slug();
	}
	//--------------------------------------------------------------------------------
    public function on_update(&$obj, &$current_obj) {
		
		if($obj->is_empty("new_date_created"))
		    $obj->new_date_created = \LiquidedgeApp\Octoapp\app\app\date\date::strtodatetime();
	}
	//--------------------------------------------------------------------------------
    public function on_delete(&$obj) {

        $newsletter_file_item_arr = $obj->get_newsletter_file_item_arr();
        foreach ($newsletter_file_item_arr as $newsletter_file_item) $newsletter_file_item->delete();

    }
	//--------------------------------------------------------------------------------

    /**
     * @param $newsletter
     * @param array $options
     * @return array|false|mixed|db_newsletter_file_item
     */
    public function get_newsletter_file_item($newsletter, $options = []) {

        $sql = \LiquidedgeApp\Octoapp\app\app\db\sql\select::make();
		$sql->select("newsletter_file_item.*");
		$sql->from("newsletter_file_item");
		$sql->and_where("nfi_ref_newsletter = $newsletter->id");
		$sql->extract_options($options);

		return \core::dbt("newsletter_file_item")->get_fromsql($sql->build());

    }
	//--------------------------------------------------------------------------------

    /**
     * @param $newsletter
     * @param array $options
     * @return array|false|mixed|db_newsletter_file_item[]
     */
    public function get_newsletter_file_item_arr($newsletter, $options = []) {

		$sql = \LiquidedgeApp\Octoapp\app\app\db\sql\select::make();
		$sql->select("newsletter_file_item.*");
		$sql->from("newsletter_file_item");
		$sql->and_where("nfi_ref_newsletter = $newsletter->id");
		$sql->extract_options($options);

		return \core::dbt("newsletter_file_item")->get_fromsql($sql->build(), ["multiple" => true]);
    }
    //--------------------------------------------------------------------------------
	public function build_slug($obj, $options = []) {

	    $options = array_merge([
	        "append" => []
	    ], $options);

	    $new_name_parts = \LiquidedgeApp\Octoapp\app\app\str\str::str_to_word_arr($obj->format_name());

		$name_arr = [];
		$name_arr[] = implode("-", array_slice($new_name_parts, 0, 5));

		if($obj->is_empty($this->key)) $name_arr[] = $obj->get_next_id();
		else $name_arr[] = $obj->id;

		$append_arr = \LiquidedgeApp\Octoapp\app\app\arr\arr::splat($options["append"]);
		foreach ($append_arr as $append) $name_arr[] = $append;

		return \LiquidedgeApp\Octoapp\app\app\str\str::str_to_seo(implode("-", $name_arr));
	}
    //--------------------------------------------------------------------------------

}