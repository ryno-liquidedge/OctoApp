<?php
namespace LiquidedgeApp\Octoapp\app\app\solid\property_set\intf;

/**
 * Class standard
 * @package app\property_set\intf
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

abstract class standard extends \LiquidedgeApp\Octoapp\app\app\intf\standard {

    /**
     * @var \db_property_config
     */
    public $property_config;

    protected static $is_singleton = true;

    //--------------------------------------------------------------------------------
    /**
     * @param \db_property_config $property_config
     */
    public function set_property_config($property_config): void {
        $this->property_config = $property_config;
    }
    //--------------------------------------------------------------------------------
    public function get_property_config($options = []) {

        $options = array_merge([
            "force" => false,
            "field" => false,
            "default" => false,
        ], $options);

        if(!$this->property_config || $options["force"])
            $this->property_config = \core::dbt("property_config")->find([".prc_key" => $this->get_key()]);

        if($options["field"]){
            if($this->property_config) return $this->property_config->{$options["field"]};
            else return  $options["default"];
        }

        return $this->property_config;
    }
    //--------------------------------------------------------------------------------

	/**
	 * This is an optional method that provides an in-depth description of the property
	 */
	public function get_description():string{ return ""; }
    //--------------------------------------------------------------------------------
	/**
	 * The display name of the property
	 * @return string
	 */
	abstract public function get_display_name();
	//--------------------------------------------------------------------------------
	/**
	 * The code used to build the constant
	 * @return string
	 */
	abstract public function get_code();
	//--------------------------------------------------------------------------------

	/**
	 * The GS1 key of the property
	 * @return string
	 */
	abstract public function get_key();
	//--------------------------------------------------------------------------------

	/**
	 * The data type of the property
	 * @return mixed
	 */
	abstract public function get_data_type();
	//--------------------------------------------------------------------------------
	public function get_data_arr():array { return []; }
	//--------------------------------------------------------------------------------
	public function get_data_arr_value($key, $options = []){
		$options = array_merge([
		    "default" => false
		], $options);

		$data_arr = $this->get_data_arr();

		return isset($data_arr[$key]) ? $data_arr[$key] : $options["default"];
	}
	//--------------------------------------------------------------------------------
	public function get_data_type_str() {
		switch ($this->get_data_type()) {
			case \com\data::TYPE_ENUM 		: return "TYPE_ENUM";
			case \com\data::TYPE_SET 		: return "TYPE_SET";
			case \com\data::TYPE_INT 		: return "TYPE_INT";
			case \com\data::TYPE_FLOAT 		: return "TYPE_FLOAT";
			case \com\data::TYPE_CURRENCY 	: return "TYPE_CURRENCY";
			case \com\data::TYPE_PERCENTAGE : return "TYPE_PERCENTAGE";
			case \com\data::TYPE_BOOL 		: return "TYPE_BOOL";
			case \com\data::TYPE_TELNR 		: return "TYPE_TELNR";
			case \com\data::TYPE_STRING 	: return "TYPE_STRING";
			case \com\data::TYPE_TEXT 		: return "TYPE_TEXT";
			case \com\data::TYPE_XML 		: return "TYPE_XML";
			case \com\data::TYPE_DATE 		: return "TYPE_DATE";
			case \com\data::TYPE_DATETIME 	: return "TYPE_DATETIME";
			case \com\data::TYPE_YEARMONTH 	: return "TYPE_YEARMONTH";
			case \com\data::TYPE_TIME 		: return "TYPE_TIME";
			case \com\data::TYPE_REFERENCE 	: return "TYPE_REFERENCE";
			case \com\data::TYPE_EMAIL 		: return "TYPE_EMAIL";
			case \com\data::TYPE_FILE 		: return "TYPE_FILE";
			case \com\data::TYPE_HTML 		: return "TYPE_HTML";
			case \com\data::TYPE_RAW 		: return "TYPE_RAW";
			case \com\data::TYPE_CONTROLLER	: return "TYPE_CONTROLLER";
			case \com\data::TYPE_DBIDENT	: return "TYPE_DBIDENT";
			case \com\data::TYPE_ACTION		: return "TYPE_ACTION";
			case \com\data::TYPE_ALPHA		: return "TYPE_ALPHA";
			case \com\data::TYPE_ALPHANUM	: return "TYPE_ALPHANUM";
			case \com\data::TYPE_MINUTES	: return "TYPE_MINUTES";
			case \com\data::TYPE_BYTES		: return "TYPE_BYTES";
			case \com\data::TYPE_MILISECONDS: return "TYPE_MILISECONDS";
			case \com\data::TYPE_SECONDS	: return "TYPE_SECONDS";
			case \com\data::TYPE_KEY		: return "TYPE_KEY";
			case \com\data::TYPE_CLASS		: return "TYPE_CLASS";
			case \com\data::TYPE_JSON		: return "TYPE_JSON";
		}
	}
	//--------------------------------------------------------------------------------
	public function get_constant_string_name() {
		if(defined(strtoupper($this->get_key()))){
            $constant_str = $this->get_key();
        }else{
            $user_constant_arr = get_defined_constants(true)["user"];
            $constant_str = array_search($this->get_key(), $user_constant_arr);
        }
        return strtoupper($constant_str);
	}
	//--------------------------------------------------------------------------------
    public function get_generated_data_entry() {
    	$constant_str = $this->get_constant_string_name();
    	$solid_class_arr = \app\solid\property_set\incl\library::$solid_arr;

    	return isset($solid_class_arr[$constant_str]) ? $solid_class_arr[$constant_str] : [];
	}
	//--------------------------------------------------------------------------------

	/**
	 * This method is used to determine if the value of this property can be updated in octo from this shop
	 * @return false
	 */
	public function allow_external_override($options = []){

	    $options = array_merge([
	        "default" => false,
	    ], $options);

	    $options["field"] = "prc_allow_external_override";

		return (bool) $this->get_property_config($options);
	}
	//--------------------------------------------------------------------------------

	/**
	 * @return false
	 */
	public function allow_logging($options = []){

	    $options = array_merge([
	        "default" => false,
	    ], $options);

	    $options["field"] = "prc_allow_logging";

		return (bool) $this->get_property_config($options);
	}
	//--------------------------------------------------------------------------------
    /**
     * @return bool
     */
	public function is_editable($options = []){

	    $options = array_merge([
	        "default" => false,
	    ], $options);

	    $options["field"] = "prc_is_editable";

		return (bool) $this->get_property_config($options);
	}
	//--------------------------------------------------------------------------------
    /**
     * @return bool
     */
	public function is_enabled($options = []){

	    $options = array_merge([
	        "default" => false,
	    ], $options);

	    $options["field"] = "prc_is_enabled";

		return (bool) $this->get_property_config($options);
	}
	//--------------------------------------------------------------------------------
    /**
     * @return string
     */
	public function get_table_name(): string {
	    $called_class = get_called_class();

	    $parts = explode("\\", $called_class);

	    return $parts[sizeof($parts)-2];
	}
	//--------------------------------------------------------------------------------
    /**
     * @return string
     */
	public function get_form_id(): string {
		return strtolower($this->get_code());
	}
	//--------------------------------------------------------------------------------

	/**
	 * The default value of the property
	 * @return mixed
	 */
	public function get_default(){
		return \LiquidedgeApp\Octoapp\app\app\data\data::parse(false, $this->get_data_type());
	}
	//--------------------------------------------------------------------------------

	/**
	 * @param $value
	 * @return bool
	 */
	public function is_empty($value): bool {
		return $value == $this->get_default() || isempty($value);
	}
	//--------------------------------------------------------------------------------

    /**
     * Parses the value to the appropriate data type
     * @param $mixed
     * @param array $options
     * @return mixed|string
     */
	public function parse($mixed, $options = []){
		return \LiquidedgeApp\Octoapp\app\app\data\data::parse($mixed, $this->get_data_type(), $options);
	}
	//--------------------------------------------------------------------------------
	/**
	 * @param $mixed
	 * @return mixed
	 */
	public function format($mixed, $options = []){
		$options = array_merge([
		    "default" => $this->get_default()
		], $options);

		switch ($this->get_data_type()){
			case \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_BOOL: return $mixed ? "Yes" : "No";
			case \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_CURRENCY: return \LiquidedgeApp\Octoapp\app\app\num\num::currency($this->parse($mixed));
			case \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_ENUM: return isset($this->get_data_arr()[$this->parse($mixed)]) ? $this->get_data_arr()[$this->parse($mixed)] : $options["default"];
			default: return $this->parse($mixed);
		}
	}
	//--------------------------------------------------------------------------------
}
