<?php


require_once '../../vendor/autoload.php';
include_once '../factory/api_form.php';

$api = get_api();
if(!$api) return;

try {

    //custom call
	$api->enable_cache(true);
	$api->set_cache_expire(10);
    $response = $api->call([
        "method" => "GET",
    ]);

    \octoapi\core\com\debug\debug::view("batch_id - ".$response->get_batch_id());
    \octoapi\core\com\debug\debug::view("overall_prep_time - ".$response->get_overall_prep_time());
    \octoapi\core\com\debug\debug::view("total_prepared_records - ".$response->get_total_prepared_records());
    \octoapi\core\com\debug\debug::view("total_records - ".$response->get_total_records());
    \octoapi\core\com\debug\debug::view($response->get_response_data());
    \octoapi\core\com\debug\debug::view($response);

} catch (Exception $e) {
    \octoapi\core\com\debug\debug::view($e->getMessage());
}

