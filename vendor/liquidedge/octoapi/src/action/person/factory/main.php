<?php

namespace octoapi\action\person\factory;


class main extends \octoapi\core\com\intf\standard {

	const OCTO_ROLE_USER = "user";
	const OCTO_ROLE_CLIENT = "client";
	const OCTO_ROLE_COMPANY = "company";
	const OCTO_ROLE_WAREHOUSE = "warehouse";
	const OCTO_ROLE_REGION = "region";
	const OCTO_ROLE_AGENT = "agent";
	const OCTO_ROLE_LANDLORD = "landlord";
	const OCTO_ROLE_TENANT = "tenant";
	const OCTO_ROLE_ORGANIZATION = "organization";
	const OCTO_ROLE_VISITOR = "visitor";
	const OCTO_ROLE_BRANCH_ADMIN = "branch_admin";
	const OCTO_ROLE_BRANCH_EMPLOYEE = "branch_employee";
	const OCTO_ROLE_COMPLIANCE = "compliance";
	const OCTO_ROLE_CONTACT_PERSON = "contact_person";
	const OCTO_ROLE_DESIGNER = "designer";
	const OCTO_ROLE_FUNDER = "funder";
	const OCTO_ROLE_ISSUER = "issuer";
	const OCTO_ROLE_OFFICE_MANAGER = "office_manager";
	const OCTO_ROLE_SELLER = "seller";
	const OCTO_ROLE_SALES_AGENT = "sales_agent";
	const OCTO_ROLE_AGENCY_OFFICE_MANAGER = "agency_office_manager";
	const OCTO_ROLE_AGENCY_OWNER = "agency_owner";
	const OCTO_ROLE_SENIOR_AGENT = "senior_agent";
	const OCTO_ROLE_AGENCY_SUPER_ADMIN = "agency_super_admin";
	const OCTO_ROLE_SUPPLIER = "supplier";

    //------------------------------------------------------------------------------------------------------------------
    public function get(): \octoapi\action\person\get {
        return \octoapi\action\person\get::make();
    }
    //------------------------------------------------------------------------------------------------------------------
    public function post(): \octoapi\action\person\post {
        return \octoapi\action\person\post::make();
    }
    //------------------------------------------------------------------------------------------------------------------

}