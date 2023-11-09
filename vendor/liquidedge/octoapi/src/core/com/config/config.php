<?php

namespace octoapi\core\com\config;

/**
 * @package octoapi\core\com\config
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class config extends \octoapi\core\com\intf\standard {

    protected string $url;
    protected string $username;
    protected string $password;

    //------------------------------------------------------------------------------------------------------------------
    protected function __construct($options = []) {
        $options = array_merge([
            "url" => "",
            "username" => "",
            "password" => "",
        ], $options);

        if($options["url"]) $this->set_url($options["url"]);
        if($options["username"]) $this->set_username($options["username"]);
        if($options["password"]) $this->set_password($options["password"]);
    }
    //------------------------------------------------------------------------------------------------------------------

    /**
     * @return string
     */
    public function get_url(): string {
        return $this->url;
    }
    //------------------------------------------------------------------------------------------------------------------

    /**
     * @param string $url
     */
    public function set_url(string $url): void {
        $this->url = $url;
    }
    //------------------------------------------------------------------------------------------------------------------

    /**
     * @return string
     */
    public function get_username(): string {
        return $this->username;
    }
    //------------------------------------------------------------------------------------------------------------------

    /**
     * @param string $username
     */
    public function set_username(string $username): void {
        $this->username = $username;
    }
    //------------------------------------------------------------------------------------------------------------------

    /**
     * @return string
     */
    public function get_password(): string {
        return $this->password;
    }
    //------------------------------------------------------------------------------------------------------------------

    /**
     * @param string $password
     */
    public function set_password(string $password): void {
        $this->password = $password;
    }
	//--------------------------------------------------------------------------------
}