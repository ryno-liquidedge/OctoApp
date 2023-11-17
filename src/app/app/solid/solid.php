<?php

namespace LiquidedgeApp\Octoapp\app\app\solid;

/**
 * @package app
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class solid extends \LiquidedgeApp\Octoapp\app\app\intf\standard {

    /**
     * @var \LiquidedgeApp\Octoapp\app\app\solid\property_set\helper|\com\intf\standard|null
     */
    public $helper = null;

	//--------------------------------------------------------------------------------
	// static functions
	//--------------------------------------------------------------------------------
    protected function __construct($options = []) {

        $this->helper = \LiquidedgeApp\Octoapp\app\app\solid\property_set\helper::make();

    }
    //--------------------------------------------------------------------------------

    /**
     * @param $key
     * @param array $options
     * @return mixed|\LiquidedgeApp\Octoapp\app\app\solid\property_set\intf\standard
     */
    public static function get_instance($key, $options = []) {
    	$constant_str = self::get_constant_string_name($key);
        return \LiquidedgeApp\Octoapp\app\app\solid\solid::make()->helper->get_instance($constant_str, $options);
 	}
    //--------------------------------------------------------------------------------

    /**
     * @param $key
     * @return mixed|\LiquidedgeApp\Octoapp\app\app\solid\property_set\solid_classes\settings\intf\standard
     */
    public static function get_setting_instance($key) {
        $constant_str = self::get_constant_string_name($key);
        return \LiquidedgeApp\Octoapp\app\app\solid\solid::make()->helper->get_instance($constant_str);
 	}
    //--------------------------------------------------------------------------------
    public static function get_constant_string_name($mixed) {
        if(defined(strtoupper($mixed))){
            $constant_str = $mixed;
        }else{
            $user_constant_arr = get_defined_constants(true)["user"];
            $constant_str = array_search($mixed, $user_constant_arr);
        }
        return strtoupper($constant_str);
    }
    //--------------------------------------------------------------------------------
    public static function get_data_arr($key, $options = []){

        $constant_str = self::get_constant_string_name($key);
        $solid = \LiquidedgeApp\Octoapp\app\app\solid\solid::get_instance($constant_str);
        return $solid->get_data_arr();
    }
    //--------------------------------------------------------------------------------
    public static function get_generated_data_entry($key) {
    	$constant_str = self::get_constant_string_name($key);
    	$solid_class_arr = \app\solid\property_set\incl\library::$solid_arr;

    	return isset($solid_class_arr[$constant_str]) ? $solid_class_arr[$constant_str] : [];
	}
    //--------------------------------------------------------------------------------
    public static function request($key, $options = []){

        $constant_str = self::get_constant_string_name($key);
        $solid = \LiquidedgeApp\Octoapp\app\app\solid\solid::get_instance($constant_str);

        $datatype = $solid->get_data_type();

        if($datatype == \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_ENUM)
        	$datatype = \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_STRING;

        $options = array_merge([
            "default" => $solid->get_default(),
            "datatype" => $datatype,
        ], $options);

        return \core::$app->get_request()->get($solid->get_form_id(), $options["datatype"], $options);
    }
    //--------------------------------------------------------------------------------
    public static function request_setting($key, $options = []){

        $constant_str = self::get_constant_string_name($key);
        $solid = \LiquidedgeApp\Octoapp\app\app\solid\solid::get_setting_instance($constant_str);

        $options = array_merge([
            "default" => $solid->get_default()
        ], $options);

        return \core::$app->get_request()->get($solid->get_form_id(), $solid->get_data_type(), $options);
    }
    //--------------------------------------------------------------------------------

    /**
     * @param $key
     * @return mixed|\LiquidedgeApp\Octoapp\app\app\solid\property_set\solid_classes\settings\intf\standard
     */
    public static function get_setting_instance_from_id($key) {
        return \LiquidedgeApp\Octoapp\app\app\solid\solid::get_setting_instance(constant(strtoupper($key)));
 	}
    //--------------------------------------------------------------------------------

	/**
	 * @param $key
	 * @return string
	 */
    public static function get_form_id($key) {
    	$solid = self::get_instance($key);
        return $solid->get_form_id();
 	}
    //--------------------------------------------------------------------------------
    public static function install($options = []) {

        $options = array_merge([
            "install_db_class" => false
        ], $options);

        \LiquidedgeApp\Octoapp\app\app\solid\property_set\factory\library_builder::make()->run();
        \LiquidedgeApp\Octoapp\app\app\solid\property_set\factory\constant_builder::make()->run();
    }
    //--------------------------------------------------------------------------------

    /**
     * @param $acl_code
     * @return false|mixed|\LiquidedgeApp\Octoapp\app\app\solid\install\acl_role\master\main
     */
    public static function get_instance_acl_role($acl_code) {
        $acl_code = strtolower($acl_code);
        if (file_exists(\core::$folders->get_app_app()."/install/acl_role/app.install.acl_role.{$acl_code}.php")){
            return call_user_func( ["\\app\\install\\acl_role\\{$acl_code}", "make"] );
        }
    }
    //--------------------------------------------------------------------------------
}