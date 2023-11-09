<?php

namespace octoapi\action\product;

/**
 * @package octoapi\core\action\product
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class post extends \octoapi\core\com\intf\post {

    //------------------------------------------------------------------------------------------------------------------
    // construct
    //------------------------------------------------------------------------------------------------------------------

	/**
	 * @param $id_arr
	 * @param array $options
	 * @return \octoapi\core\com\octo\response
	 * @throws \Exception
	 */
    public function decline_share($id_arr, $options = []): \octoapi\core\com\octo\response {

        $options = array_merge([
        	"action" => "?c=rest2/company/v2/product/.decline_share",
            "product_id_arr" => $id_arr,
            "method" => "POST",
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
	 * @param $id_arr - list of product ID's
	 * @param $remote_owner_id - Owner id to share to
	 * @param $remote_user_id_arr - this is a list of user ID's linked to the owner id
	 * @param array $options
	 * @return \octoapi\core\com\octo\response
	 * @throws \Exception
	 */
    public function share_product(array $id_arr, int $remote_owner_id, array $remote_user_id_arr, $options = []): \octoapi\core\com\octo\response {

        $options = array_merge([
        	"action" => "?c=rest2/company/v2/product/.share",
            "method" => "POST",

			"filter_arr" => [
				"pro_id_arr" => $id_arr,
			],
			"to_company_arr" => [
				$remote_owner_id => [
					"person_arr" => $remote_user_id_arr,
				],
			],
			"shared_by" => [
				"firstname" => "",
				"lastname" => "",
				"cellnr" => "",
				"email" => "",
			]


        ], $options);

        $api_options = $this->get_api_options();
        $api_options->set_action($options["action"]);
        $api_options->apply_options($options);

        return $this->get_service()
            ->set_options($api_options)
            ->call();
    }
    //------------------------------------------------------------------------------------------------------------------
    public function accept_share($id_arr, $options = []): \octoapi\core\com\octo\response {
    	$options = array_merge([
        	"action" => "?c=rest2/company/v3/product/.accept_share",
            "method" => "POST",

            "product_id_arr" => $id_arr,
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
	 * @param $data_arr
	 * @param array $options
	 * @return \octoapi\core\com\octo\response
	 * @throws \Exception
	 */
    public function push_product($data_arr, $options = []): \octoapi\core\com\octo\response {

        $options = array_merge([
        	"action" => "?c=rest2/company/v3/product/.push_product",
            "method" => "POST",

            "allow_new" => true,
            "product_arr" => $data_arr,
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