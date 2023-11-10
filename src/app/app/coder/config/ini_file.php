<?php

namespace LiquidedgeApp\Octoapp\app\app\coder\config;

class ini_file extends \LiquidedgeApp\Octoapp\app\app\intf\standard {

	/**
	 * @var \WriteiniFile\WriteiniFile
	 */
	public $instance;

	public static $filename = false;

	public array $property_arr = [
		"standard" => [
			"company" => "",
			"website" => "",
		],
		"custom" => [

			"app.currency.remove.decimals" => false,
			"app.currency.vat.amount" => 15,

			"email.no_reply" => "",
			"email.from" => "",
			"email.accounts" => "",
			"email.contact" => "",
			"email.admin" => "",
			"email.order" => "",
			"email.support" => "",
			"email.repairs" => "",
			"email.sales" => "",
			"email.appointment" => "",

			"tell.nr.office" => "",
			"tell.nr.contact" => "",
			"tell.nr.general" => "",
			"tell.nr.sales" => "",
			"tell.nr.fax" => "",

			"company.reg_no" => "",
			"company.vat_no" => "",

			"company.address.physical.str" => "",
			"company.address.postal.str" => "",

			"bs.color.primary" => "",
			"bs.color.secondary" => "",
			"bs.color.success" => "",
			"bs.color.info" => "",
			"bs.color.danger" => "",
			"bs.color.warning" => "",
			"bs.color.light" => "",
			"bs.color.dark" => "",
		]
	];


	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {

		self::$filename = \core::$folders->get_app()."/config/config.ini";

		$this->init($options);
	}
	//--------------------------------------------------------------------------------
	private function init($options = []){

		\LiquidedgeApp\Octoapp\app\app\os\os::mkdir(dirname(self::$filename));

		if(file_exists(self::$filename)){

			$data_arr = \WriteiniFile\ReadiniFile::get(self::$filename);
			foreach ($data_arr as $group => $group_arr){
				array_walk($group_arr, function($value, $id) use($group){
					$this->property_arr[$group][$id] = $value;
				});
			}
		}else{
			$this->build();
		}

	}
	//--------------------------------------------------------------------------------
	public function get_option($group, $key, $options = []) {
		$options = array_merge([
		    "default" => false
		], $options);

		return isset($this->property_arr[$group][$key]) ? $this->property_arr[$group][$key] : $options["default"];
	}
	//--------------------------------------------------------------------------------
	public function set_option($group, $key, $value) {
		$this->property_arr[$group][$key] = $value;
	}
	//--------------------------------------------------------------------------------
	public function build($options = []) {

		$options = array_merge([
		    "force" => true
		], $options);

		// return if the config file is already created
		if(file_exists(self::$filename) && !$options["force"])
			return;

		$file = new \WriteiniFile\WriteiniFile(self::$filename);
		$file->create($this->property_arr);
		$file->write();

	}
	//--------------------------------------------------------------------------------
}