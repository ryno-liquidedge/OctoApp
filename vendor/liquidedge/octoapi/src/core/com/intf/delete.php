<?php

namespace octoapi\core\com\intf;

/**
 * @package octoapi\core\action
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 *
 * The DELETE method is used to delete a resource specified by its URI.
 */
abstract class delete extends standard {

    use \octoapi\core\com\tra\action;

	//--------------------------------------------------------------------------------
    public function get_api_options(): \octoapi\core\com\octo\api_options {

        if(!$this->api_options)
            $this->api_options = \octoapi\core\com\octo\api_options::make();

        $this->api_options->set_method("DELETE");

        return $this->api_options;

    }

	//--------------------------------------------------------------------------------
}