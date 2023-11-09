<?php

namespace LiquidedgeApp\Octoapp\app\app\http;


class cookie extends \LiquidedgeApp\Octoapp\app\app\intf\standard {

    protected $cookie;

    //--------------------------------------------------------------------------------
    public function __construct() {

        $this->cookie = $_COOKIE;

    }
    //--------------------------------------------------------------------------------
    public function __get($name) {

        $name = $this->clean_name($name);

        return $this->get($name);
    }
    //--------------------------------------------------------------------------------
    public function clean_name($name) {
        return str_replace(["=", " ", ";", ","], "-", $name);
    }
    //--------------------------------------------------------------------------------
    public function set($name, $value, $options = []) {
        $options_arr = array_merge([
            "encrypt" => false,
            "expire" => 0,
        ], $options);

        if($options_arr["encrypt"]) $value = \com\encryption::encrypt($value);

        $name = $this->clean_name($name);

        setcookie($name, $value, $options_arr["expire"]);
    }
    //--------------------------------------------------------------------------------
    public function remove_cookie($name, $options = []) {
        $options_arr = array_merge([
        ], $options);

        if(isset($_COOKIE[$name])){
            $this->set($name, false);
            unset($_COOKIE[$name]);
        }
    }
    //--------------------------------------------------------------------------------
    public function get($name, $options = []) {

        $options_arr = array_merge([
            "default" => false,
            "decrypt" => false,
        ], $options);

        $name = $this->clean_name($name);

        $value = isset($_COOKIE[$name]) ? $_COOKIE[$name] : $options_arr["default"];

        if($options_arr["decrypt"] && $value) $value = \com\encryption::decrypt($value);

        return $value;
    }
    //--------------------------------------------------------------------------------
}