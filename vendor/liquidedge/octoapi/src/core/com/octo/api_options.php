<?php

namespace octoapi\core\com\octo;


class api_options extends \octoapi\core\com\intf\standard {

    protected $action;
    protected $method;

    protected int $page_size = 0;
    protected int $offset = 0;

    protected array $options = [
        "batch_id" => false,
		"page_size" => 200,
		"offset" => 0,
		"filter_arr" => [
			"last_updated" => false,
			"id_arr" => [],
		],
		"data_filter" => []
    ];

	//--------------------------------------------------------------------------------
    public function set_filter_last_updated($date) {

        $date = date_create($date);
        $date = date_format($date,"Y-m-d G:i:s");

        $this->options["filter_arr"]["last_updated"] = $date;
    }
	//--------------------------------------------------------------------------------
    public function set_filter_id_arr($id_arr) {
        $this->options["filter_arr"]["id_arr"] = $id_arr;
    }
	//--------------------------------------------------------------------------------
    public function set_filter($key, $value) {
        $this->options["filter_arr"][$key] = $value;
    }
	//--------------------------------------------------------------------------------

    /**
     * @param mixed $method
     */
    public function set_method($method): void {
        $this->method = $method;
    }
	//--------------------------------------------------------------------------------

    /**
     * @return mixed
     */
    public function get_method() {
        return $this->method;
    }
	//--------------------------------------------------------------------------------

    /**
     * @return \octoapi\core\com\intf\standard|\octoapi\core\com\config\config
     */
    public function get_config() {
        return \octoapi\OctoApi::get_config();
    }
	//--------------------------------------------------------------------------------

    /**
     * @param mixed $action
     */
    public function set_action($action): void {
        $this->action = $action;
    }
	//--------------------------------------------------------------------------------
    /**
     * @return mixed
     */
    public function get_action() {
        return $this->action;
    }
	//--------------------------------------------------------------------------------
    /**
     * @param int $page_size
     */
    public function set_page_size(int $page_size): void {
        $this->page_size = $page_size;
    }
	//--------------------------------------------------------------------------------
    public function apply_options($options = []) {
        $this->options = array_merge($this->options, $options);
    }
	//--------------------------------------------------------------------------------
    /**
     * @param int $offset
     */
    public function set_offset(int $offset): void {
        $this->offset = $offset;
    }
	//--------------------------------------------------------------------------------
    public function add_option($index, $value) {
        $this->options[$index] = $value;
    }
	//--------------------------------------------------------------------------------
    public function get_options(): array {

        $fn_property = function($field){
            if($this->{$field} && !isset($this->options[$field])) $this->options[$field] = $this->{$field};
        };

        $fn_property("page_size");
        $fn_property("offset");

        if(isset($this->options["filter_arr"]["id_arr"]) && $this->options["filter_arr"]["id_arr"] && !$this->options["page_size"])
            $this->options["page_size"] = sizeof($this->options["filter_arr"]["id_arr"]);

        if(!$this->options["page_size"]) $this->options["page_size"] = 200;

        return $this->options;
    }
	//--------------------------------------------------------------------------------
}