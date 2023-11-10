<?php
namespace LiquidedgeApp\Octoapp\app\app\solid\property_set\solid_classes\settings\intf;

/**
 * Class standard
 * @package app\property_set\intf
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

abstract class standard extends \LiquidedgeApp\Octoapp\app\app\solid\property_set\intf\standard {

    /**
     * @var \com\session|null
     */
    protected $session;

	//--------------------------------------------------------------------------------
    protected function __construct($options = []) {

        $this->session = \core::$app->get_session();

    }
	//--------------------------------------------------------------------------------
    public function on_save(&$obj){}
	//--------------------------------------------------------------------------------
    public function is_empty($options = []) :bool {

        $obj = $this->get_setting($options);

        if(!$obj) return true;
        return (bool) $obj->is_empty("stt_value");
    }
	//--------------------------------------------------------------------------------
    public function save_value($value, $options = []) {

	    $options = array_merge([
	        "stt_is_enabled" => true
	    ], $options);

        $obj = \core::dbt("settings")->find([
            ".stt_key" => $this->get_key(),
            "create" => true,
        ]);

        $obj->stt_value = $this->parse($value);
        $obj->stt_is_enabled = $options["stt_is_enabled"];
        $this->on_save($obj);
        $obj->save();

        if($this->session) $this->session->{$this->get_form_id()} = $obj;
    }
	//--------------------------------------------------------------------------------

    /**
     * @param array $options
     * @return array|false|mixed|\db_settings
     */
    public function get_setting($options = []) {

        $options = array_merge([
            "force" => false,
            "multiple" => false,
            "create" => false,
        ], $options);

        $enable_session = !$options["force"] && !$options["multiple"] && $this->session;

        if($enable_session && property_exists($this->session, $this->get_form_id())){
            return $this->session->get($this->get_form_id(), $this->get_default());
        }

	    $sql = \LiquidedgeApp\Octoapp\app\app\db\sql\select::make();
	    $sql->select("settings.*");
	    $sql->from("settings");
	    $sql->and_where("stt_key = ".dbvalue($this->get_key()));
	    $sql->extract_options($options);

        $obj = \core::dbt("settings")->get_fromsql($sql->build(), $options);

        if($options["create"] && !$obj){
        	message(false);
        	$obj = \core::dbt("settings")->get_fromdefault();
        	$obj->stt_key = $this->get_key();
        	$obj->stt_value = $this->get_default();
        	$obj->save();
        	message(true);
		}

        if($enable_session) $this->session->{$this->get_form_id()} = $obj;


        return $obj;
    }
	//--------------------------------------------------------------------------------
    public function get_value($options = []) {
        $options = array_merge([
            "format" => false,
            "default" => $this->get_default(),
        ], $options);

        $setting = $this->get_setting($options);

        if(!$setting) return $options["default"];

        if($options["format"])
        	return $this->format($setting->stt_value);

        return $this->parse($setting->stt_value);
    }
	//--------------------------------------------------------------------------------
}
