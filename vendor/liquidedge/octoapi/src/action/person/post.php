<?php

namespace octoapi\action\person;
/**
 * @package octoapi\action\product
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class post extends \octoapi\core\com\intf\post {

    //------------------------------------------------------------------------------------------------------------------
    // construct
    //------------------------------------------------------------------------------------------------------------------
    /**
     * @param array $options
     * @return \octoapi\core\com\octo\response
     * @throws \Exception
     */
    public function add_person($options = []): \octoapi\core\com\octo\response {

        $options = array_merge([
        	"action" => "?c=rest2/company/v3/person/.register_user",
            "firstname" => false,
			"lastname" => false,
			"email" => false,
			"cellnr" => false,
            "birthday" => false,

			"password" => false,
			"empty_password" => false,

            "role_arr" => [],
        ], $options);

        $api_options = $this->get_api_options();
        $api_options->set_action($options["action"]);
        $api_options->apply_options($options);

        return $this->get_service()
            ->set_options($api_options)
            ->call();
    }
    //------------------------------------------------------------------------------------------------------------------
}