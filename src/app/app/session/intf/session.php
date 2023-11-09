<?php

namespace LiquidedgeApp\Octoapp\app\app\session\intf;

/**
 * Class session
 * @package app\session\intf
 * @author Ryno Van Zyl
 */

abstract class session extends \LiquidedgeApp\Octoapp\app\app\intf\standard {

    protected $name = "";

    /**
     * @var bool|\com\session
     */
    protected $com_session = false;

    /**
     * @var bool | $this
     */
    protected $instance = false;

    /**
     * @var \com\router\int\request|null
     */
    public $request;

    private array $empty_exclude_param_arr = ["name", "com_session", "instance", "order", "singleton_arr", "is_singleton", "request", "empty_exclude_param_arr"];

    //--------------------------------------------------------------------------
    //properties
    //--------------------------------------------------------------------------
    protected function __construct($options = []) {

        $options = array_merge([
            "name" => false
        ], $options);

        if(!$options["name"]) $options["name"] = str_replace("\\", "_", get_called_class());

        $this->name = $options["name"];
        $this->com_session = \core::$app->get_session();
        $this->request = \core::$app->get_request();

        $this->init();
    }
    //--------------------------------------------------------------------------
    protected function on_update($options = []){}
    protected function on_clear($options = []){}
    protected function on_init($options = []){}

    //--------------------------------------------------------------------------
    protected function get_instance() {
        return $this;
    }
    //--------------------------------------------------------------------------------
    /**
     * make safe name
     * @param $key
     * @return mixed
     */
    protected function format_key($key){
        //make safe name
        return str_replace([".", "/", "\\"], "_", $key);

    }
    //--------------------------------------------------------------------------
	public function exclude_param_on_empty($field) {
		$this->empty_exclude_param_arr[$field] = $field;
	}
    //--------------------------------------------------------------------------
    public function is_empty() {

        $class_vars = get_class_vars(get_called_class());
        $object_vars = get_object_vars($this);
        foreach ($class_vars as $key => $value){

            if(in_array($key, $this->empty_exclude_param_arr)) continue;

            if($object_vars[$key] !== $class_vars[$key]) {
            	return false;
			}
        }

        return true;

    }
    //--------------------------------------------------------------------------
    public function __set($name, $value) {

        //make safe name
        $name = $this->format_key($name);

        $this->{$name} = $value;
    }
    //--------------------------------------------------------------------------
    public function __get($name) {

        //make safe name
        $name = $this->format_key($name);

        if(isset($this->{$name})) return $this->{$name};
    }
    //--------------------------------------------------------------------------
    public function get($name, $default = false) {

        //make safe name
        $name = $this->format_key($name);

        //evaluate and return default
		if (!isset($this->{$name})) return $this->{$name} = $default;

		//return
		return $this->{$name};
	}

    //--------------------------------------------------------------------------
    private function init(){

    	$this->on_init();

    	if($this->com_session){
			$this->instance = $this->com_session->get($this->name);
			if($this->instance) $this->merge();
		}
    }
    //--------------------------------------------------------------------------
    private function merge() {
        $session_properties = $this->instance ? get_object_vars($this->instance) : [];
        foreach ($session_properties as $property => $value) {
            $this->get_instance()->{$property} = $value;
        }
    }
    //--------------------------------------------------------------------------
    public function update($options = []){
    	$this->on_update($options);
        $this->com_session->{$this->name} = $this;
    }
    //--------------------------------------------------------------------------

    /**
     * @param $session_name
     * @param array $options
     * @return \LiquidedgeApp\Octoapp\app\app\session\standard
     */
    public static function create($session_name, $options = []){
        return call_user_func( ["\\app\\session\\$session_name","make"], $options);
    }
    //--------------------------------------------------------------------------
    public function clear($options = []){

        $this->on_clear($options);

        //unset session
        if(isset($_SESSION[$this->name])){
            unset($_SESSION[$this->name]);
            unset($this->com_session->{$this->name});
        }

        //set defaults
        $default_arr = get_class_vars(get_called_class());
        foreach ($default_arr as $property => $default){
            $this->{$property} = $default;
        }
    }
    //--------------------------------------------------------------------------
}