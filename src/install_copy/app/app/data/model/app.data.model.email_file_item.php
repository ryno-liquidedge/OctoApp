<?php

namespace app\data\model;

/**
 * @package app\data\model
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class email_file_item extends \com\core\model\email_file_item {
    //--------------------------------------------------------------------------------
    // magic
    //--------------------------------------------------------------------------------
    protected function __construct() {
        // parent
        parent::__construct();

        $this->storage = \com\data\storage\dbt::with($this, \core::dbt("email_file_item"));
    }
    //--------------------------------------------------------------------------------
	public function get_prefix() {
		return $this->get_obj()->get_prefix();
	}
	//--------------------------------------------------------------------------------
	public function _find_with_email($email_id) {
		return \app\data\model\email_file_item::make()->find(
			\com\data\opt\find::make()
				->and_where_equal("emf_ref_email", $email_id)
		);
	}
    //--------------------------------------------------------------------------------
}