<?php

namespace action\developer\system_user\functions;

/**
 * Class xedit
 * @package action\system\setup\system_user\functions
 * @author Ryno Van Zyl
 */


class xedit implements \com\router\int\action {
    //--------------------------------------------------------------------------------
	use \com\router\tra\action;
    //--------------------------------------------------------------------------------
    public function auth() { return \core::$app->get_token()->check("admins"); }
    //--------------------------------------------------------------------------------
    public function run () {

        message(false);
        $acl_code_arr = $this->request->get('acl_code_arr', \com\data::TYPE_STRING, ["default" => []]);

        $person = $this->request->getobj("person", true);
        if($person->is_empty("per_password")) unset($person->per_password);
        $person->update();

        $acl_code_arr = \com\arr::splat($acl_code_arr);
        if($acl_code_arr){
            foreach ($acl_code_arr as $acl_code) {
                $acl_role = \core::dbt("acl_role")->splat($acl_code);
                if(!$person->has_role($acl_role->acl_code)){
                    $person->add_role($acl_role->acl_code);
                }
            }

            $sql = \com\db\sql\select::make();
            $sql->select("person_role.*");
            $sql->from("person_role");
            $sql->from("LEFT JOIN acl_role ON (pel_ref_acl_role = acl_id)");
            $sql->and_where(\com\db::getsql_in($acl_code_arr, "acl_code", false, true));
            $sql->and_where("pel_ref_person = $person->id");

            $person_role_arr = \core::dbt("person_role")->get_fromsql($sql->build(), ["multiple" => true]);
            foreach ($person_role_arr as $remove_acl_role) {
                $remove_acl_role->delete();
            }
        }else{
            //remove all roles
            $person_role_arr = \core::dbt("person_role")->get_fromdb("pel_ref_person = $person->id", ["multiple" => true]);
            foreach ($person_role_arr as $remove_acl_role) {
                $remove_acl_role->delete();
            }
        }

        message(true, "Changes Saved");

    }
    //--------------------------------------------------------------------------------
}





