<?php

namespace octoapi\core\com\intf;

/**
 * @package octoapi\core\action
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 *
 * The PUT method is most often used to update an existing resource.
 * If you want to update a specific resource (which comes with a specific URI), you can call the PUT method to that resource URI with the request body containing the complete new version of the resource you are trying to update.
 */
abstract class put extends standard {

    use \octoapi\core\com\tra\action;

	//--------------------------------------------------------------------------------
    public function get_api_options(): \octoapi\core\com\octo\api_options {

        if(!$this->api_options)
            $this->api_options = \octoapi\core\com\octo\api_options::make();

        $this->api_options->set_method("PUT");

        return $this->api_options;

    }

    //--------------------------------------------------------------------------------
}