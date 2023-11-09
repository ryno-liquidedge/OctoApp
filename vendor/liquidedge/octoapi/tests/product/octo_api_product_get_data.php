<?php


require_once '../../vendor/autoload.php';
include_once '../factory/api_form.php';

$api = get_api();
if(!$api) return;

try {
    $filter_id = request_value("filter_id");
    if(!$filter_id) return;

    $filter_id_arr = explode(",", $filter_id);
    $filter_id = reset($filter_id_arr);

    $response = $api->product()->get()->get_product_data($filter_id);

    \octoapi\core\com\debug\debug::view("batch_id - ".$response->get_batch_id());
    \octoapi\core\com\debug\debug::view("overall_prep_time - ".$response->get_overall_prep_time());
    \octoapi\core\com\debug\debug::view("total_prepared_records - ".$response->get_total_prepared_records());
    \octoapi\core\com\debug\debug::view("total_records - ".$response->get_total_records());
    \octoapi\core\com\debug\debug::view($response->get_response_data_first());

} catch (Exception $e) {
    \octoapi\core\com\debug\debug::view($e->getMessage());
}

