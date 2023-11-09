<?php

namespace octoapi\core\com\intf;

/**
 * @package octoapi\core\action
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 *
 * The GET method is used to retrieve data from the server.
 * This is a read-only method, so it has no risk of mutating or corrupting the data.
 *
 * For example, if we call the get method on our API, we’ll get back a list of all to-dos.
 *
 */
abstract class get extends standard {

    use \octoapi\core\com\tra\action;

	//--------------------------------------------------------------------------------
    public function get_api_options(): \octoapi\core\com\octo\api_options {

        if(!$this->api_options)
            $this->api_options = \octoapi\core\com\octo\api_options::make();

        $this->api_options->set_method("GET");

        return $this->api_options;

    }
    //--------------------------------------------------------------------------------
}