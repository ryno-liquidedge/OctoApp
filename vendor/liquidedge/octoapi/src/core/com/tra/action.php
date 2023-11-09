<?php

namespace octoapi\core\com\tra;


trait action  {

    /**
     * @var \octoapi\core\com\octo\api_options
     */
    private $api_options;

	//--------------------------------------------------------------------------------
    /**
     * @return \octoapi\core\com\octo\api_options
     */
    public function get_api_options(): \octoapi\core\com\octo\api_options {

        if($this->api_options) return $this->api_options;

        return \octoapi\core\com\octo\api_options::make();
    }
	//--------------------------------------------------------------------------------
    public function set_api_options($api_options) {
        $this->api_options = $api_options;
    }
	//--------------------------------------------------------------------------------

    /**
     * @param array $options
     * @return \octoapi\core\com\intf\standard|\octoapi\core\com\config\config
     */
    protected function get_config($options = []) {
        return \octoapi\OctoApi::get_config();
    }
	//--------------------------------------------------------------------------------

    /**
     * @param array $options
     * @return \octoapi\core\com\octo\service
     */
    protected function get_service($options = []) {
        $options = array_merge([
            "config" => $this->get_config()
        ], $options);

        return \octoapi\core\com\octo\service::make($options);
    }
	//--------------------------------------------------------------------------------
}