<?php

namespace LiquidedgeApp\Octoapp\app\db;

/**
 * Database Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class settings extends \com\db\table {

    use \LiquidedgeApp\Octoapp\app\app\db\tra\table;

    //--------------------------------------------------------------------------------
    // properties
    //--------------------------------------------------------------------------------
    public $name = "settings";
    public $key = "stt_id";
    public $display = "stt_key";
    
    public $string = "stt_key";
    public $display_name = "settings";
    public $audit = false;
    public $trace = false;

    public $field_arr = array(// FIELDNAME => DISPLAY[0] DEFAULT[1] TYPE[2] REFERENCE[3]
        // identification
        "stt_id"                            => array("id",                  "null",     DB_INT),
        "stt_key"                           => array("key",                 "",         DB_STRING),
        "stt_value"                         => array("value",               "",         DB_HTML),
        "stt_type"                          => array("type",                0,          DB_ENUM),
        "stt_date_created"                  => array("date created",        "null",     DB_DATETIME),
        "stt_date_modified"                 => array("date modified",       "null",     DB_DATETIME),
        "stt_is_custom"                     => array("is custom",           0,          DB_BOOL),
        "stt_is_enabled"					=> array("is enabled",			0,			DB_BOOL),
        "stt_db_id"							=> array("db id",				0,			DB_INT),
        "stt_table"							=> array("ref table",			"",         DB_STRING),
    );
    
    //--------------------------------------------------------------------------------
	// enums
    //--------------------------------------------------------------------------------
    public $stt_type = [
        0 => "-- Not Selected --",
    ];
    //--------------------------------------------------------------------------------
    // events
    //--------------------------------------------------------------------------------
    public function on_auth(&$obj, $user, $role) {
        return true;
    }
    //--------------------------------------------------------------------------------
    public function on_auth_use(&$obj, $user, $role) {
        return true;
    }
    //--------------------------------------------------------------------------------
    public function on_insert(&$obj) {
        parent::on_insert($obj);
        $obj->stt_date_created = \LiquidedgeApp\Octoapp\app\app\date\date::strtodatetime();
        $obj->stt_date_modified = \LiquidedgeApp\Octoapp\app\app\date\date::strtodatetime();
    }
    //--------------------------------------------------------------------------------
    public function on_update(&$obj, &$current_obj) {
        parent::on_update($obj, $current_obj);
        $obj->stt_date_modified = \LiquidedgeApp\Octoapp\app\app\date\date::strtodatetime();
    }
    //--------------------------------------------------------------------------------
    public function on_update_complete(&$obj, &$current_obj) {
        $solid = \app\solid::get_setting_instance($current_obj->stt_key);
        if($solid->get_data_type() == \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_FILE){
            if($current_obj->stt_value && !isnull($current_obj->stt_value)){
                $file_item = \core::dbt("file_item")->get_fromdb($current_obj->stt_value);
                $file_item->delete();
            }
        }
    }

    //--------------------------------------------------------------------------------
    public function on_delete_complete(&$obj) {
        $solid = \app\solid::get_setting_instance($obj->stt_key);
        if($solid->get_data_type() == \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_FILE){
            if($obj->stt_value && !isnull($obj->stt_value)){
                $file_item = \core::dbt("file_item")->get_fromdb($obj->stt_value);
                $file_item->delete();
            }
        }
    }

    //--------------------------------------------------------------------------------
    public static function get_value($key, $options = []) {

        $solid_class = \app\solid::get_setting_instance($key);
        return $solid_class->get_value($options);

    }
    //--------------------------------------------------------------------------------

    public static function is_installed($options = []) {

		$sql = \LiquidedgeApp\Octoapp\app\app\db\sql\select::make();
		$sql->select("COUNT(TABLE_NAME)");
		$sql->from("information_schema.TABLES ");
		$sql->and_where("TABLE_SCHEMA LIKE ".dbvalue(\core::$app->get_instance()->get_db_name()));
		$sql->and_where("TABLE_NAME = 'settings'");

		return (bool) \core::db()->selectsingle($sql->build());

    }
    //--------------------------------------------------------------------------------
    public static function is_configured($key, $options = []) {

        $solid_class = \app\solid::get_setting_instance($key);
        return !$solid_class->is_empty($options);

    }
    //--------------------------------------------------------------------------------

    /**
     * @param $key
     * @param array $options
     * @return array|db_settings|false|mixed|db_settings
     */
    public static function get_setting($key, $options = []) {

        $options = array_merge([
            "force" => true
        ], $options);

        $solid_class = \app\solid::get_setting_instance($key);
        return $solid_class->get_setting($options);

    }
    //--------------------------------------------------------------------------------
    public static function is_cart_enabled() {
        return self::get_value(SETTING_ENABLE_CART);
    }
    //--------------------------------------------------------------------------------
    public static function save_setting($key, $value) {
        $solid = \app\solid::get_setting_instance($key);
        $solid->save_value($value);
    }
    //--------------------------------------------------------------------------------
    public static function delete_setting($key, $options = []) {

        $constant_str = \app\solid::get_constant_string_name($key);

    	$options = array_merge([
    	    ".stt_key"  => constant($constant_str),
    	], $options);

		$sql = \LiquidedgeApp\Octoapp\app\app\db\sql\select::make();
		$sql->select("settings.*");
		$sql->from("settings");
		$sql->and_where("stt_key = ".dbvalue(constant($constant_str)));
		$sql->extract_options($options);

        $setting = \core::dbt("settings")->get_fromsql($sql->build());

        if($setting) $setting->delete();
    }
    //--------------------------------------------------------------------------------

    /**
     * @param array $options
     * @return \com\db\row|\com\db\row[]|\com\db\table|\com\db\table[]|false|db_address
     */
    public static function get_company_address($options = []) {

        message(false);

        $add_id = self::get_value(SETTING_COMPANY_COMPANY_ADDRESS_REF, $options);
        $exists = \core::dbt("address")->exists("add_id = ".dbvalue($add_id));
        if(isnull($add_id) || !$add_id || !$exists){
            $address = \core::dbt("address")->get_fromdefault();
			$address->add_nomination = 1;
			$address->add_type = 1;
			$add_id = $address->insert();

			self::save_setting(SETTING_COMPANY_COMPANY_ADDRESS_REF, $add_id);

        }

        $address = \core::dbt("address")->get_fromdb($add_id);

        message(true);

        return $address;
    }
    //--------------------------------------------------------------------------------

    /**
     * @param array $options
     * @return \com\db\row|\com\db\row[]|\com\db\table|\com\db\table[]|false|db_file_item
     */
    public static function get_file_item($key, $options = []) {

        $options = array_merge([
            "default" => false
        ], $options);

        $default = $options["default"];
        unset($options["default"]);

        $fil_id = self::get_value($key, $options);
        if($fil_id && !isnull($fil_id)) return \core::dbt("file_item")->get_fromdb($fil_id);

        return $default;

    }
    //--------------------------------------------------------------------------------

	/**
	 * @param array $options
	 * @return \com\db\row|\com\db\row[]|\com\db\table|\com\db\table[]|db_file_item|false
	 */
    public static function get_favicon_stream($options = []) {

		$options = array_merge([
		    "default" => \core::$folders->get_root_files()."/standard/favicon.png",
		], $options);

    	return \app\http::get_stream_url(self::get_file_item(SETTING_COMPANY_COMPANY_FAVICON_REF, $options));
    }
    //--------------------------------------------------------------------------------

    /**
     * @param array $options
     * @return \com\db\row|\com\db\row[]|\com\db\table|\com\db\table[]|false|db_file_item
     */
    public static function get_logo($options = []) {

        return self::get_company_logo_light($options);
    }
    //--------------------------------------------------------------------------------

    /**
     * @param array $options
     * @return \com\db\row|\com\db\row[]|\com\db\table|\com\db\table[]|false|db_file_item
     */
    public static function get_company_logo_light($options = []) {

    	$options = array_merge([
    	    "default" => \core::$folders->get_root_files()."/standard/placeholder-maxarea_400x150.jpg"
    	], $options);

        $file_item = self::get_file_item(SETTING_COMPANY_COMPANY_LOGO_LIGHT_REF);
        if($file_item) return $file_item;

        if(file_exists(\core::$folders->get_root_files()."/img/logo.png"))
        	return \core::$folders->get_root_files()."/img/logo.png";

        return $options["default"];
    }
    //--------------------------------------------------------------------------------

    /**
     * @param array $options
     * @return \com\db\row|\com\db\row[]|\com\db\table|\com\db\table[]|false|db_file_item
     */
    public static function get_company_logo_dark($options = []) {

    	$options = array_merge([
    	    "default" => \core::$folders->get_root_files()."/standard/placeholder-maxarea_400x150.jpg"
    	], $options);

        $file_item = self::get_file_item(SETTING_COMPANY_COMPANY_LOGO_DARK_REF);
        if($file_item) return $file_item;

        if(file_exists(\core::$folders->get_root_files()."/img/logo_dark.png"))
        	return \core::$folders->get_root_files()."/img/logo_dark.png";

        return $options["default"];

    }
    //--------------------------------------------------------------------------------
}
