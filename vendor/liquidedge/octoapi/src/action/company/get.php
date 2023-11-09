<?php

namespace octoapi\action\company;
/**
 * @package octoapi\action\person
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class get extends \octoapi\core\com\intf\get {

    //------------------------------------------------------------------------------------------------------------------
    // construct
    //------------------------------------------------------------------------------------------------------------------
    public function setup($options = []): \octoapi\core\com\octo\response {

    	$options = array_merge([
    	    "action" => "?c=rest2/company/v3/company/.setup",
    	], $options);

        $api_options = $this->get_api_options();
        $api_options->set_action($options["action"]);
        $api_options->apply_options($options);

        return $this->get_service()
            ->set_options($api_options)
            ->call();
    }
    //------------------------------------------------------------------------------------------------------------------
    public function get_available_shop_list($options = []): \octoapi\core\com\octo\response {

    	$options = array_merge([
    	    "action" => "?c=rest2/company/v3/company/.available_shop_list",
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