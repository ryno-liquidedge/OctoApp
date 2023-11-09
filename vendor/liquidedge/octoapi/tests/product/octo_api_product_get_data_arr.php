<?php


require_once '../../vendor/autoload.php';
include_once '../factory/api_form.php';

$api = get_api();
if(!$api) return;

$filter_id = request_value("filter_id");
$filter_id_arr = [];
if($filter_id){
    $filter_id_arr = explode(",", $filter_id);
    foreach ($filter_id_arr as $index => $id) $filter_id_arr[$index] = trim($id);
}

try {
    $response = $api->product()->get()->get_product_data_arr([
        "filter_arr" => [
            "last_updated" => false,
            "id_arr" => $filter_id_arr,
        ],
        "page_size" => request_value("page_size"),
        "offset" => request_value("offset"),
    ]);

    \octoapi\core\com\debug\debug::view("batch_id - ".$response->get_batch_id());
    \octoapi\core\com\debug\debug::view("overall_prep_time - ".$response->get_overall_prep_time());
    \octoapi\core\com\debug\debug::view("total_prepared_records - ".$response->get_total_prepared_records());
    \octoapi\core\com\debug\debug::view("total_records - ".$response->get_total_records());
    \octoapi\core\com\debug\debug::view($response->get_response_data());

} catch (Exception $e) {
    \octoapi\core\com\debug\debug::view($e->getMessage());
}

