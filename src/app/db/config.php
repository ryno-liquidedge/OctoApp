<?php

namespace LiquidedgeApp\Octoapp\app\db;

/**
 * Database Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class config extends \com\core\db\config {
	
	use \LiquidedgeApp\Octoapp\app\app\db\tra\table;
	
	//--------------------------------------------------------------------------------
	// properties
	//--------------------------------------------------------------------------------
	public $name = "config";
	public $key = "cfg_id";
	public $display = "cfg_name";

	public $display_name = "config";

	public $field_arr = array( // FIELDNAME => DISPLAY[0] DEFAULT[1] TYPE[2] REFERENCE[3]
		// general
		"cfg_id"					=> array("id"				    	, "null"	, DB_KEY),
		"cfg_name"					=> array("name"				    	, ""		, DB_STRING),
		"cfg_is_active"				=> array("is active"            	, 0			, DB_BOOL),
		"cfg_bulksms_reply_id"		=> array("latest bulksms reply id" 	, 0			, DB_INT),

		// components
		"cfg_min_flash_version"		=> array("minimum flash version"	, ""		, DB_STRING),

		"cfg_ref_person"			=> array("user"						, "null"	, DB_REFERENCE	, "person"),
		"cfg_data"					=> array("data"						, ""		, DB_JSON),
		"cfg_class"					=> array("class"					, ""		, DB_CLASS		, "com\\core\\factory\\config"),
		"cfg_date_updated"			=> array("date updated"				, "null"	, DB_DATETIME),
	);
    //--------------------------------------------------------------------------------
	// events
    //--------------------------------------------------------------------------------
	public function on_save(&$config) {
		// date updated
		if ($config->has_changed()) {
			$config->cfg_date_updated = \LiquidedgeApp\Octoapp\app\app\date\date::strtodatetime();
		}
		$this->sanitize_field_arr($config);
	}
 	//--------------------------------------------------------------------------------
	// functions
 	//--------------------------------------------------------------------------------
	public function on_auth(&$obj, $user, $role) {
		return \core::$app->get_token()->check('users');
	}
    //--------------------------------------------------------------------------------
	public function on_auth_use(&$obj, $user, $role) {
		return \core::$app->get_token()->check('users');
	}
	//--------------------------------------------------------------------------------
    public static function get($cfg_name, $options = []) {

        $options = array_merge([
            "field" => false,
            "default" => false,
            "datatype" => \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_STRING,
            "create" => false,
        ], $options);

        $config = \core::dbt("config")->find([".cfg_name" => $cfg_name, "create" => $options["create"]]);

        if($config && $options["field"]){
            if(property_exists($config, $options["field"])){

                $value = $config->{$options["field"]};

                if(!$value) return $options["default"];

                return \LiquidedgeApp\Octoapp\app\app\data\data::parse($value, $options["datatype"]);
            }
            return $options["default"];
        }

        return $config ? $config : $options["default"];
    }
    //--------------------------------------------------------------------------------
    public static function value($cfg_name, $options = []) {

        $options = array_merge([
            "field" => "cfg_data",
            "default" => false,
            "datatype" => \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_TEXT,
            "create" => false,
        ], $options);

        return \LiquidedgeApp\Octoapp\app\db\config::get($cfg_name, $options);
    }
    //--------------------------------------------------------------------------------

	/**
	 * @param $key
	 * @param array $options
	 * @return mixed
	 * @throws Exception
	 */
    public static function get_config_file_item($key, $options = []) {

    	$options = array_merge([
    	    "path" => false
    	], $options);

    	//get from DB
        $config = \LiquidedgeApp\Octoapp\app\db\config::get($key, ["create" => true]);

        //save to db settings if it does not exist and path is not empty
		if($config->source != "database" && $options["path"] && file_exists($options["path"])){
			$app_file = \com\file\manager\file_item::make();
			$file_item = $app_file->save_from_file($options["path"]);

			//new entry
			$config->cfg_data = $file_item->fil_id;
			$config->save();
		}

		return $config;

    }
    //--------------------------------------------------------------------------------

	/**
	 * @param $key
	 * @param array $options
	 * @return mixed|db_file_item
	 * @throws Exception
	 */
    public static function get_file_item($key, $options = []) {
    	$options = array_merge([
    	    "path" => false
    	], $options);

        $config = \LiquidedgeApp\Octoapp\app\db\config::get_config_file_item($key, $options);
        if($config && !$config->is_empty("cfg_data")){
			return \core::dbt("file_item")->get_fromdb(\LiquidedgeApp\Octoapp\app\app\data\data::parse_reference($config->cfg_data));
		}
    }
 	//--------------------------------------------------------------------------------
}