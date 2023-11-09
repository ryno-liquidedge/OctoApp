<?php

namespace octoapi\core\com\octo;


class response extends \octoapi\core\com\intf\standard {

    protected $source = "API";
    protected $response_data = [];

    protected $message_arr = [
		// success
		0	=> ["http_code" => 200, "message" => "Your request was successfully processed."],

		// low level web service messages
		200 => ["http_code" => 500, "message" => "There was a problem while processing your request. We have been notified and are working on fixing it."],
		201 => ["http_code" => 501, "message" => "The resource you have requested does not exist or the method is not available on the resource."],
		202 => ["http_code" => 401, "message" => "Your session does not exist or has expired."],
		203 => ["http_code" => 401, "message" => "Authentication failed."],
		204 => ["http_code" => 403, "message" => "Access denied."],
		205 => ["http_code" => 410, "message" => "Version no longer supported."],

		// auth issue
		206	=> ["http_code" => 403, "message" => "Unauthorized"],
		207	=> ["http_code" => 403, "message" => "Person not linked to company"],
		208	=> ["http_code" => 403, "message" => "Service Already in use"],

		// low level web service messages
		303 => ["http_code" => 403, "message" => "Required parameter(s) missing."],
		304 => ["http_code" => 403, "message" => "One or more parameter(s) empty."],

		403 => ["http_code" => 200, "message" => "No company found"],
		404 => ["http_code" => 404, "message" => "Resource not found."],
		405 => ["http_code" => 404, "message" => "Product not found for this company"],
		406 => ["http_code" => 200, "message" => "License not found"],
		407 => ["http_code" => 200, "message" => "License already activated"],

		408 => ["http_code" => 404, "message" => "No Entity found"],
		409 => ["http_code" => 404, "message" => "No Entry found at Branch"],
		410 => ["http_code" => 404, "message" => "No Entity found"],
		411 => ["http_code" => 404, "message" => "No Entity found"],

		412 => ["http_code" => 200, "message" => "License not active"],

		501 => ["http_code" => 404, "message" => "No products found"],

		704 => ["http_code" => 404, "message" => "Person not found with remote ID, No email found, cannot get/create user."],

	];

	//--------------------------------------------------------------------------------
    protected function __construct($options = []) {
        $this->response_data = $options;
    }
    //--------------------------------------------------------------------------
    public function parse_error_code($code) {

        if(isset($this->message_arr[$code]))
            return $this->message_arr[$code];

        return ["http_code" => 500, "message" => "There was a problem while processing your request."];
    }
    //--------------------------------------------------------------------------
	public function get_response($key = false, $options = []) {

		$options = array_merge([
		    "default" => []
		], $options);

		if(!$this->response_data) return $options["default"];

		if(!$key) return $this->response_data;
		else return $this->extract_from_arr($this->response_data, $key, $options);

	}
    //--------------------------------------------------------------------------
	/**
	 * @return string
	 */
	public function get_source(): string {
		return $this->source;
	}
    //--------------------------------------------------------------------------
	/**
	 * @param string $source
	 */
	public function set_source(string $source): void {
		$this->source = $source;
	}
    //--------------------------------------------------------------------------
	public function get_batch_id(){
        return $this->extract_from_arr($this->get_response_meta(), "batch_id", ["default" => false]);
	}
	//--------------------------------------------------------------------------
	public function get_total_records(){
        return $this->extract_from_arr($this->get_response_meta(), "total_batch_records", ["default" => 0]);
	}
	//--------------------------------------------------------------------------
	public function get_total_prepared_records(){
        return $this->extract_from_arr($this->get_response_meta(), "total_prepared_records", ["default" => 0]);
	}
	//--------------------------------------------------------------------------
	public function get_page_size(){
        return $this->extract_from_arr($this->get_response_meta(), "page_size", ["default" => 0]);
	}
	//--------------------------------------------------------------------------
	public function get_offset(){
        return $this->extract_from_arr($this->get_response_meta(), "offset", ["default" => 0]);
	}
	//--------------------------------------------------------------------------
	public function get_overall_prep_time(){
        return $this->extract_from_arr($this->get_response_meta(), "overall_prep_time", ["default" => 0]);
	}
	//--------------------------------------------------------------------------
	public function get_response_body(): array {
		return $this->get_response("body");
	}
	//--------------------------------------------------------------------------
	public function get_response_meta() {
		return $this->extract_from_arr($this->get_response_body(), "meta");
	}
	//--------------------------------------------------------------------------
	public function get_response_code() {
		return $this->extract_from_arr($this->get_response_body(), "code");
	}
	//--------------------------------------------------------------------------
	public function has_error(): bool {
		return $this->get_response_code() != 0;
	}
	//--------------------------------------------------------------------------
	public function get_response_message() {
		$mixed = $this->extract_from_arr($this->get_response_body(), "message", ["default" => null]);

		if(is_array($mixed)) $mixed = json_encode($mixed);

		return $mixed;
	}
	//--------------------------------------------------------------------------
	public function get_response_data() {
		return $this->extract_from_arr($this->get_response_body(), "data");
	}
	//--------------------------------------------------------------------------
	public function get_response_data_first() {
        $response_data = $this->get_response_data();
		return reset($response_data);
	}
	//--------------------------------------------------------------------------
	public function extract_from_arr($arr, $key, $options = []){
		return \octoapi\core\com\arr\arr::extract_from_arr($arr, $key, $options);
	}
    //--------------------------------------------------------------------------------
}