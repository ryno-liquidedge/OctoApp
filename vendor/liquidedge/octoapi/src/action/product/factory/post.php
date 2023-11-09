<?php

namespace octoapi\action\product\factory;

class post extends \octoapi\core\com\intf\post {

    protected string $product_name;
    protected int $remote_id;
    protected string $key;

    protected array $classification_arr = [];

    protected array $brand_arr = [];

    protected array $property_arr = [];

    protected array $asset_arr = [];

    //------------------------------------------------------------------------------------------------------------------
    /**
     * @param string $product_name
     */
    public function set_product_name(string $product_name): void {
        $this->product_name = $product_name;
    }
    //------------------------------------------------------------------------------------------------------------------

    /**
     * @param int $remote_id
     */
    public function set_remote_id(int $remote_id): void {
        $this->remote_id = $remote_id;
    }
    //------------------------------------------------------------------------------------------------------------------

    /**
     * @param string $key
     */
    public function set_key(string $key): void {
        $this->key = $key;
    }
    //------------------------------------------------------------------------------------------------------------------

    /**
     * @param $name - The name of the entry
     * @param $remote_id - This is the target ID of the data entry in the remote Octo data source. If no id is supplied a new entry is created
     * @param false $parent_remote_id - This is the parent target ID of the data entry in the remote Octo data source.
     * @param array $options
     */
    public function add_classification($name, $remote_id = false, $parent_remote_id = false, $options = []): void {
        $this->classification_arr[] = array_merge([
            "cla_name" => $name,
            "octo_cla_id" => $remote_id,
            "octo_cla_ref_classification" => $parent_remote_id,
        ], $options);
    }
    //------------------------------------------------------------------------------------------------------------------
    /**
     * @param $name - The name of the entry
     * @param $remote_id - This is the target ID of the data entry in the remote Octo data source. If no id is supplied a new entry is created
     * @param array $options
     */
    public function add_brand($name, $remote_id = false, $options = []): void {
        $this->brand_arr[] = array_merge([
            "bra_name" => $name,
            "octo_bra_id" => $remote_id,
        ], $options);
    }
    //------------------------------------------------------------------------------------------------------------------

    /**
     * @param $key - This is the GS1 key of the property
     * @param $value - A value that is linked to this property
     * @param array $options
     */
    public function add_property($key, $value, $options = []): void {
        $this->property_arr[] = array_merge([
            "pdp_key" => $key,
            "pdp_value" => $value,
        ], $options);
    }
    //------------------------------------------------------------------------------------------------------------------

    /**
     * @param $name
     * @param $url
     * @param $type - The asset type. Can be: youtube | vimeo | vistia | link | document | image
     * @param false $remote_id
     * @param array $options
     * @throws \Exception
     */
    public function add_asset($name, $url, $type, $remote_id = false, $options = []) {

        if(!in_array($type, [
            "youtube",
            "vimeo",
            "vistia",
            "link",
            "document",
            "image",
        ])) throw new \Exception("The asset type [{$type}] provided is not a valid option");

        $this->asset_arr[] = array_merge([
            "name" => $name,
            "url" => $url,
            "type" => $type,
            "remote_id" => $remote_id,
        ], $options);
    }
    //------------------------------------------------------------------------------------------------------------------

}