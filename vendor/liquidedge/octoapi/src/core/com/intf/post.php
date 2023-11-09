<?php

namespace octoapi\core\com\intf;

/**
 * @package octoapi\core\action
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 *
 * The POST method sends data to the server and creates a new resource.
 * The resource it creates is subordinate to some other parent resource.
 * When a new resource is POSTed to the parent, the API service will automatically associate the new resource by assigning it an ID (new resource URI).
 *
 * In short, this method is used to create a new data entry.
 *
 */
abstract class post extends standard {

    use \octoapi\core\com\tra\action;

	//--------------------------------------------------------------------------------
    public function get_api_options(): \octoapi\core\com\octo\api_options {

        if(!$this->api_options)
            $this->api_options = \octoapi\core\com\octo\api_options::make();

        $this->api_options->set_method("POST");

        return $this->api_options;

    }

	//--------------------------------------------------------------------------------
}