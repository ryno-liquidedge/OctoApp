<?php

namespace action\developer\property_config\functions;

/**
 * Class vlist
 * @package action\system\setup\property_config
 * @author Ryno Van Zyl
 */

class xtoggle_field implements \com\router\int\action {

	//--------------------------------------------------------------------------------
	use \com\router\tra\action;

	//--------------------------------------------------------------------------------
	public function auth() { 
		return \core::$app->get_token()->check("dev");
	}

	//--------------------------------------------------------------------------------
	public function run() {

	    $property_config = $this->request->getdb("property_config", true);
	    $field = $this->request->get('field', \com\data::TYPE_STRING, ["trusted" => true]);

	    $property_config->{$field} = !(bool)$property_config->{$field};
	    $property_config->update();

    }
	//--------------------------------------------------------------------------------
}
