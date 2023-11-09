<?php

namespace octoapi\action\person;
/**
 * @package octoapi\action\person
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class get extends \octoapi\core\com\intf\get {

    //------------------------------------------------------------------------------------------------------------------
    // construct
    //------------------------------------------------------------------------------------------------------------------
    /**
     * Returns a key / ID (remote DB ID) list
     * @param array $options
     * @return \octoapi\core\com\octo\response
     * @throws \Exception
     */
    public function get_list($options = []): \octoapi\core\com\octo\response {

    	$options = array_merge([
        	"action" => "?c=rest2/company/v3/person/.list",
        ], $options);

        $api_options = $this->get_api_options();
        $api_options->set_action($options["action"]);
        $api_options->apply_options($options);

        return $this->get_service()
            ->set_options($api_options)
            ->call();
    }
    //------------------------------------------------------------------------------------------------------------------

    /**
     * Returns specific data for multiple persons
     * @param array $options
     * @return \octoapi\core\com\octo\response
     * @throws \Exception
     */
    public function get_data_arr($options = []): \octoapi\core\com\octo\response {

        $options = array_merge([
        	"action" => "?c=rest2/company/v3/person/.person_sync_v2",
            "page_size" => false,
            "filter_arr" => [
				"last_updated" => false,
				"id_arr" => [],
			],
        ], $options);

        $api_options = $this->get_api_options();
        $api_options->set_action($options["action"]);
        $api_options->apply_options($options);

        return $this->get_service()
            ->set_options($api_options)
            ->call();
    }
    //------------------------------------------------------------------------------------------------------------------

    /**
     * Returns specific data for a product
     * @param $remote_id
     * @param array $options
     * @return \octoapi\core\com\octo\response
     * @throws \Exception
     */
    public function get_person_data($remote_id, $options = []): \octoapi\core\com\octo\response {

        $options = array_merge([
        	"action" => "?c=rest2/company/v3/person/.person_sync_v2",
            "page_size" => false,
            "filter_arr" => [
				"last_updated" => false,
				"id_arr" => [$remote_id],
			],
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