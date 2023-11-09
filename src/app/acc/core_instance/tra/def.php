<?php

namespace LiquidedgeApp\Octoapp\app\acc\core_instance\tra;

trait def{

    //--------------------------------------------------------------------------------
    protected function apply_defaults() {

    	$this->company = "";
		$this->system = "Nova";
		$this->title = "";
		$this->website = "";

        $this->db_charset = "ISO-8859-1";

        $this->email_retry_limit = 5;

        $this->format_date = "Y-m-d";
        $this->format_datetime = "Y-m-d H:i:s";
        $this->format_time = "H:i";

        $this->sms_force_to = "";
		$this->sms_system_id = "mrz";
		$this->sms_price = "0.25";

        $this->google_maps_api_key = "";
        $this->google_maps_api_key = "";
        $this->recaptcha_secret_key = false;
		$this->recaptcha_site_key = false;

        $this->set_option("com.encryption.key", "Yy4fNecheqAT/rXyFgfo4jGeLbG5ttrCPILqZtlpPkA=");
        $this->set_option("tinify.api.key", "38NnbLwjQRrcL3Dd6rq4cxD7DqCwMdq2");

		$this->set_option("company.address", []);

		$this->set_option("company.address.postal", []);

		$this->set_option("vat", 15);
		define("VAT", 15);

		$this->set_option("com.db.disable_hex2bin", true);

		$this->set_option("database.old.host", "dedi698.jnb1.host-h.net");
		$this->set_option("database.old.username", "grovebudhf_2");
		$this->set_option("database.old.password", "efNnQN9zuPW6VefPpE88");
		$this->set_option("database.old.name", "shop_grovebudhf_db2");

		if(file_exists(\core::$folders->get_app()."/config/config.ini")){
			$this->apply_ini_options(\core::$folders->get_app()."/config/config.ini");
		}

		$this->copyright = '© Copyright '.$this->company.' '.\com\date::strtodate("now", "Y").'.<br>All rights reserved.<br>Website Powered by<svg id="liquidlogo" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" width="23" height="25" viewBox="0 0 66 89.92">
          <title>Liquid Edge Solutions</title>
          <rect width="66" height="89.92" style="fill: none;"></rect>
          <g>
            <path d="M57.67,1.27h0a1.27,1.27,0,0,0-.84.32L7.21,43.74a1.33,1.33,0,0,0,.87,2.35H18.27a4.71,4.71,0,0,0,3-1.12L55.59,15.83a4.73,4.73,0,0,0,1.58-2.76L59,2.9a1.53,1.53,0,0,0,0-.3A1.32,1.32,0,0,0,57.67,1.27Z" style="fill: #ffffff;"></path>
            <path d="M53.68,23.9h0a1.39,1.39,0,0,0-.85.32L29.85,43.74a1.33,1.33,0,0,0,.86,2.35h10.2A4.71,4.71,0,0,0,44,45l7.65-6.51a4.67,4.67,0,0,0,1.58-2.76L55,25.53a1,1,0,0,0,0-.3A1.34,1.34,0,0,0,53.68,23.9Z" style="fill: #ffffff;"></path>
            <path d="M43.11,52.84a4.65,4.65,0,0,0-3.62-1.71H29.06a1.33,1.33,0,0,0-1,2.18L44.35,73.12a1.35,1.35,0,0,0,1,.49,1.36,1.36,0,0,0,1.31-1.1L48.54,62a4.67,4.67,0,0,0-1-3.8Z" style="fill: #ffffff;"></path>
          </g>
        </svg>
        <a href="https://liquidedge.co.za/" class="text-white link-unstyled" target="_blank">Liquid Edge.</a>';

    }
    //--------------------------------------------------------------------------------
    public function apply_development_custom_options() {

        $this->email_smtp = "";
		$this->email_host = "";
		$this->email_from = "";
		$this->email_username = "";
		$this->email_password = "";

		$this->email_from_name = "{$this->company} DEV";


		$this->session_name = \LiquidedgeApp\Octoapp\app\app\str\str::session_name($this->get_id()."DEV");

    }
    //--------------------------------------------------------------------------------
    public function apply_test_custom_options() {

        $this->email_smtp = "";
		$this->email_host = "";
		$this->email_from = "";
		$this->email_username = "";
		$this->email_password = "";

		$this->email_from_name = "{$this->company} DEV";


		$this->session_name = \LiquidedgeApp\Octoapp\app\app\str\str::session_name($this->get_id()."UAT");

    }
    //--------------------------------------------------------------------------------
    public function apply_live_custom_options() {

        $this->email_smtp = "";
		$this->email_host = "";
		$this->email_from = "";
		$this->email_username = "";
		$this->email_password = "";

		$this->email_from_name = "{$this->company} DEV";


		$this->session_name = \LiquidedgeApp\Octoapp\app\app\str\str::session_name($this->get_id()."LIVE");

    }
    //--------------------------------------------------------------------------------
    public function apply_content_security_policy(){

        $this->content_security_policy = [
            "frame-ancestors" => ["none"],
            "frame-src" => [
                'self',
                "forms.office.com",
                "www.google.com",
                "temp1.propertyagents.co.za",
                "www.youtube.com",
            ],
            "default-src" => [
                "self",
                "https://*.google.com/recaptcha/",
                "cdnjs.cloudflare.com",
                "fonts.gstatic.com",
                "data:",
                "blob:",
                "self:",
                "maxcdn.bootstrapcdn.com",
                "cdn.jsdelivr.net",
                "temp1.propertyagents.co.za",
                "www.youtube.com",
            ],
            "script-src" => [
                "'unsafe-inline'",
                "'unsafe-eval'",
                "blob:",
                "self:",
                "ajax.googleapis.com",
                "maxcdn.bootstrapcdn.com",
                "www.google.com",
                "www.gstatic.com",
                "cdn.jsdelivr.net",
                "temp1.propertyagents.co.za",
                "www.youtube.com",
            ],
            "style-src" => [
                "'unsafe-inline'",
                "cdnjs.cloudflare.com",
                "fonts.googleapis.com",
                "maxcdn.bootstrapcdn.com",
                "cdn.jsdelivr.net",
                "temp1.propertyagents.co.za",
                "www.youtube.com",
            ],
            "img-src" => [
                "cdnjs.cloudflare.com",
                "temp1.propertyagents.co.za",
                "www.youtube.com",
                "blob:",
                "self:",
                "data:",
            ],
        ];
    }
    //--------------------------------------------------------------------------------

}

$include_arr = [
    \core::$folders->get_app_app()."/install/acl_role/incl/app.acl_role.incl.constants.php",
    \core::$folders->get_app_app()."/install/person_type/incl/app.person_type.incl.constants.php",
    \core::$folders->get_app_app()."/solid/property_set/incl/app.solid.property_set.incl.constants.php",
];

foreach ($include_arr as $include) if(file_exists($include)) include_once $include;

define("DIR_TEMP"  , \core::$folders->get_temp());
define("DIR_UI_ASSETS"  , \core::$folders->get_app_app()."/ui/inc");
define("DIR_ROOT_IMG"   , \core::$folders->get_root()."/files/img");
define("DIR_TEMP_FILES"   , \core::$folders->get_temp()."/files");

define("PEP_TYPE_INDIVIDUAL"   			, 0);
define("PEP_TYPE_COMPANY"   			, 1);
define("PEP_TYPE_EMPLOYEE"   			, 2);
define("PEP_TYPE_ADMIN"   			    , 3);
define("PEP_TYPE_BRANCH"   			    , 4);
define("PEP_TYPE_REPRESENTATIVE"   	    , 5);
define("PEP_TYPE_CONTACT"   			, 6);
define("PEP_TYPE_LANDLORD_SELLER"   	, 7);

define("PEP_TYPE_ARRAY"   				, [
	PEP_TYPE_INDIVIDUAL => "Individual",
	PEP_TYPE_COMPANY => "Company",
	PEP_TYPE_EMPLOYEE => "Employee",
	PEP_TYPE_ADMIN => "Admin",
	PEP_TYPE_BRANCH => "Branch",
	PEP_TYPE_REPRESENTATIVE => "Representative",
	PEP_TYPE_CONTACT => "Contact",
	PEP_TYPE_LANDLORD_SELLER => "Landlord / Seller",
]);


define("TYPE_GENERAL"   				        , 1);
define("TYPE_COMMERCIAL_TO_LET"   				, 2);
define("TYPE_COMMERCIAL_FOR_SALE"   		    , 3);
define("TYPE_RESIDENTIAL_TO_LET"   				, 4);
define("TYPE_RESIDENTIAL_FOR_SALE"   		    , 5);
define("TYPE_HOLIDAY_LETTING"   				, 6);
define("TYPE_STUDENT_LETTING"   				, 7);
define("TYPE_DEVELOPMENT"   				    , 8);
define("TYPE_STUDENT_BUILDING"   				, 9);
define("LISTING_TYPE_ARRAY"   				, [
    TYPE_COMMERCIAL_TO_LET 		=> "Commercial To Let",
    TYPE_COMMERCIAL_FOR_SALE 	=> "Commercial For Sale",
    TYPE_RESIDENTIAL_TO_LET 	=> "Residential To Let",
    TYPE_RESIDENTIAL_FOR_SALE 	=> "Residential For Sale",
    TYPE_HOLIDAY_LETTING 		=> "Holiday Letting",
    TYPE_STUDENT_LETTING 		=> "Student Letting",
    TYPE_DEVELOPMENT 			=> "Development",
    TYPE_STUDENT_BUILDING 		=> "Student Building",
]);


define("SLI_TYPE_SLIDER"   				        , 0);
define("SLI_TYPE_VALUED_CLIENTS"   				, 1);
define("SLI_TYPE_AFFILIATES"   					, 2);

define("SLIDER_TYPE_ARRAY"   				, [
    SLI_TYPE_SLIDER 			=> "Slider",
    SLI_TYPE_VALUED_CLIENTS 	=> "Valued Clients",
    SLI_TYPE_AFFILIATES 		=> "Affiliates",
]);

define("CONFIG_EMAIL_FILE_ITEM_LOGO"   				, "config.file.item.logo");
define("CONFIG_EMAIL_FILE_ITEM_LOGO_LARGE"   		, "config.file.item.logo.large");
define("CONFIG_DISCLAIMER"  	                    , "config.disclaimer");
define("CONFIG_POLICY"  	                        , "config.policy");
define("CONFIG_TERMS"  	                            , "config.terms");

define("SETTING_TERMS"  	                        , "setting.terms");
define("SETTING_POLICY"  	                        , "setting.policy");
define("SETTING_DISCLAIMER"  	                    , "setting.disclaimer");

define("PRO_SYNC_STATUS_NOT_PROCESSING", 0);
define("PRO_SYNC_STATUS_PROCESSING", 1);
define("PRO_SYNC_STATUS_PENDING_PUSH", 2);

//filtype group
define("FILETYPE_GROUP_WHITELISTED"       		, 0);
define("FILETYPE_GROUP_CSV"       				, 1);
define("FILETYPE_GROUP_PDF"       				, 2);
define("FILETYPE_GROUP_IMAGES"       			, 4);
define("FILETYPE_GROUP_COMPRESSED"       		, 8);
define("FILETYPE_GROUP_OFFICE"       			, 16);

define("LISTING_PERSON_AGENT"       			, 1);
define("LISTING_PERSON_LANDLORD"       			, 2);
define("LISTING_PERSON_CONTACT_PERSON"       	, 3);
define("LISTING_PERSON_SELLER"       			, 4);
define("LISTING_PERSON_ARR" ,[
	LISTING_PERSON_AGENT 				=> "Agent",
	LISTING_PERSON_LANDLORD 			=> "Landlord",
	LISTING_PERSON_CONTACT_PERSON 		=> "Contact Person",
]);

//cart shipping
define("CRT_SHIP_TO_BILLING", 1);
define("CRT_SHIP_TO_SHIPPING", 2);
define("CRT_SHIP_TO_COLLECTING", 3);
define("CRT_SHIP_TO_NONE", 4);

//cart status
define("CRT_STATUS_QUOTED"                              , 50);
define("CRT_STATUS_PROCESSING_QUOTE"                    , 55);
define("CRT_STATUS_IN_BACKLOG"                          , 70);
define("CRT_STATUS_ORDER_PLACED"                        , 100);
define("CRT_STATUS_WAITING_TO_BE_PROCESSED"             , 110);
define("CRT_STATUS_PROCESSING"                          , 112);
define("CRT_STATUS_AWAITING_PAYMENT"                    , 115);
define("CRT_STATUS_PAYMENT_FAILED"                      , 119);
define("CRT_STATUS_PAYMENT_RECEIVED"                    , 120);
define("CRT_STATUS_AWAITING_DOCUMENTATION"              , 200);
define("CRT_STATUS_DOCUMENTATION_RECIEVED"              , 210);
define("CRT_STATUS_READY_FOR_COURIER_COLLECTION"        , 300);
define("CRT_STATUS_READY_FOR_COLLECTION"                , 310);
define("CRT_STATUS_COLLECTED_BY_COURIER"                , 400);
define("CRT_STATUS_COMPLETED"                           , 500);
define("CRT_STATUS_RETURN"                              , 600);
define("CRT_STATUS_RETURN_WAITING_TO_BE_PROCESSED"      , 610);
define("CRT_STATUS_PAYMENT_REVERSAL"                    , 620);
define("CRT_STATUS_PAYMENT_REFUND"                      , 630);
define("CRT_STATUS_RETURN_COMPLETE"                     , 640);
define("CRT_STATUS_AWAITING_CONFIRMATION"               , 700);
define("CRT_STATUS_CONFIRMED"                           , 710);
define("CRT_STATUS_ORDER_FAILED"                        , 900);
define("CRT_STATUS_FAILED_COURIER_QUOTE"                , 920);
define("CRT_STATUS_CANCELLED_/_DELETED"                 , 990);
define("CRT_STATUS_UNKNOWN"                             , 999);

//status types
define("STATUS_ACTIVE"       			, 1);
define("STATUS_APPROVED"       			, 2);
define("STATUS_ACCEPTED"       			, 3);
define("STATUS_PENDING"       			, 4);
define("STATUS_DENIED"       			, 5);
define("STATUS_NEW"       				, 6);
define("STATUS_CANCELLED"    			, 7);
define("STATUS_CANCELLED_INACTIVE"   	, 8);
define("STATUS_AWAITING_PAYMENT"   		, 9);
define("STATUS_COMPLETED"   			, 10);
define("STATUS_PAYMENT_PROCESSING"   	, 11);
define("STATUS_PAYMENT_RECEIVED"   		, 12);
define("STATUS_FAILED"       			, 13);
define("STATUS_DELETED"       			, 14);
define("STATUS_SEEN"       			    , 15);
define("STATUS_CLOSED"       		    , 16);
define("STATUS_REPLIED"       		    , 17);
define("STATUS_ARCHIVED"       		    , 18);
define("STATUS_PUBLISHED"       	    , 19);
define("STATUS_DISABLED"       	        , 20);
define("STATUS_BUSY"       	            , 21);
define("STATUS_TO_BE_CONFIRMED"         , 22);
define("STATUS_RESERVED"         		, 24);
define("STATUS_RENTED"         			, 25);
define("STATUS_SOLD"         			, 26);
define("STATUS_PROCESSING"         		, 27);
define("STATUS_SUBMITTED"         		, 28);

define("SYNC_STATUS_ARRAY"   		, [
    0 						=> "Mot Synced",
    STATUS_PENDING 			=> "Pending",
    STATUS_PROCESSING 		=> "Processing",
    STATUS_COMPLETED 		=> "Done",
]);


//bank account types
define("BANK_ACCOUNT_TYPE_CHEQUE"			,1);
define("BANK_ACCOUNT_TYPE_SAVINGS"			,2);
define("BANK_ACCOUNT_TYPE_TRANSMISSION"		,3);
define("BANK_ACCOUNT_TYPE_BOND"				,4);

define("BANK_ACCOUNT_TYPE_ARR" ,[
	BANK_ACCOUNT_TYPE_CHEQUE 			=> "Cheque/Current Account",
	BANK_ACCOUNT_TYPE_SAVINGS 			=> "Savings Account",
	BANK_ACCOUNT_TYPE_TRANSMISSION 		=> "Transmission Account",
	BANK_ACCOUNT_TYPE_BOND 				=> "Bond Account",
]);

define("TRADESAFE_BANK_ACCOUNT_TYPE_ENUM" ,[
	BANK_ACCOUNT_TYPE_CHEQUE 			=> "CHEQUE",
	BANK_ACCOUNT_TYPE_SAVINGS 			=> "SAVINGS",
	BANK_ACCOUNT_TYPE_TRANSMISSION 		=> "TRANSMISSION",
	BANK_ACCOUNT_TYPE_BOND 				=> "BOND",
]);

//bank types
define("BANK_ABSA"									,1);
define("BANK_AFRICAN"								,2);
define("BANK_CAPITEC"								,3);
define("BANK_DISCOVERY"								,4);
define("BANK_FNB"									,5);
define("BANK_INVESTEC"								,6);
define("BANK_MTN"									,7);
define("BANK_NEDBANK"								,8);
define("BANK_SAPO"									,9);
define("BANK_SASFIN"								,10);
define("BANK_SBSA"									,11);
define("BANK_TYME"									,12);
define("BANK_OTHER"									,13);

define("BANK_ARR" ,[
	BANK_ABSA 			=> "Absa Bank",
	BANK_AFRICAN 		=> "African Bank",
	BANK_CAPITEC 		=> "Capitec Bank",
	BANK_DISCOVERY 		=> "Discovery Bank",
	BANK_FNB 			=> "First National Bank",
	BANK_INVESTEC 		=> "Investec Bank",
	BANK_MTN 			=> "MTN Banking",
	BANK_NEDBANK 		=> "Nedbank",
	BANK_SAPO 			=> "Postbank",
	BANK_SASFIN 		=> "Sasfin Bank",
	BANK_SBSA 			=> "Standard Bank South Africa",
	BANK_TYME 			=> "TymeBank",
	BANK_OTHER 			=> "Other",
]);

//db query types
define("DB_INSERT"       , 1);
define("DB_UPDATE"       , 2);
define("DB_DELETE"       , 3);

//asset types
define("ASSET_MAIN_IMAGE"       , 1);
define("ASSET_GALLERY_IMAGE"    , 2);
define("ASSET_DOCUMENT"         , 3);
define("ASSET_LOGO"             , 4);
define("ASSET_YOUTUBE"          , 5);
define("ASSET_VIMEO"            , 6);
define("ASSET_VISTIA"           , 7);
define("ASSET_LINK"             , 8);
define("ASSET_3D_MODEL"         , 9);
define("ASSET_IMAGE"            , 10);
define("ASSET_IMAGE_COMPANY"    , 11);
define("ASSET_IMAGE_PROFILE"    , 12);
define("ASSET_LINK_FACEBOOK"    , 13);
define("ASSET_LINK_WEBSITE"     , 14);
define("ASSET_INSURANCE_POLICY" , 15);
define("ASSET_ID_DRIVERS" 		, 16);
define("ASSET_COVER_IMAGE" 		, 17);
define("ASSET_FLOORPLAN" 		, 18);
define("ASSET_VIRTUAL_TOUR"    	, 19);


define("ASSET_ARRAY"            , [
    0 => "-- Not Selected --",
    ASSET_MAIN_IMAGE => "Main Image",
    ASSET_GALLERY_IMAGE => "Gallery Image",
    ASSET_DOCUMENT => "Document",
    ASSET_LOGO => "Logo",
    ASSET_YOUTUBE => "Youtube",
    ASSET_VIMEO => "Vimeo",
    ASSET_VISTIA => "Vistia",
    ASSET_LINK => "General Link",
    ASSET_3D_MODEL => "3d model",
    ASSET_IMAGE => "General Image",
    ASSET_IMAGE_COMPANY => "Company Image",
    ASSET_IMAGE_PROFILE => "Profile Image",
    ASSET_LINK_FACEBOOK => "Facebook Link",
    ASSET_LINK_WEBSITE => "Website Link",
	ASSET_INSURANCE_POLICY => "Insurance Policy",
	ASSET_ID_DRIVERS => "ID / Drivers",
	ASSET_COVER_IMAGE => "Cover Image",
	ASSET_FLOORPLAN => "Floor Plans",
]);

define("MONTHS", [
	1 => "January",
	2 => "February",
	3 => "March",
	4 => "April",
	5 => "May",
	6 => "June",
	7 => "July",
	8 => "August",
	9 => "September",
	10 => "October",
	11 => "November",
	12 => "December",
]);

define("WEEKDAYS", [
	1 => "Monday",
	2 => "Tuesday",
	3 => "Wednesday",
	4 => "Thursday",
	5 => "Friday",
	6 => "Saturday",
	7 => "Sunday",
]);

define("WEEKDAYS_0", [
	0 => "Monday",
	1 => "Tuesday",
	2 => "Wednesday",
	3 => "Thursday",
	4 => "Friday",
	5 => "Saturday",
	6 => "Sunday",
]);

define("BOOKING_TYPE_HOURLY", 5);
define("BOOKING_TYPE_DAILY", 6);
define("BOOKING_TYPE_HOURLY_8_17", 1);
define("BOOKING_TYPE_HOURLY_17_23", 2);
define("BOOKING_TYPE_DAILY_8_17", 3);
define("BOOKING_TYPE_DAILY_17_23", 4);

define("IMAGE_TYPE_ORIGINAL", 1);
define("IMAGE_TYPE_THUMBNAIL", 2);

define("ADV_SEARCH_BAR"         , 1);
define("ADV_SEARCH_RESULTS"     , 2);
define("ADV_PROPERTY_PAGE"      , 3);

define("ACCESS_TYPE_PRIVATE"   	, 1);
define("ACCESS_TYPE_SHARED"   	, 2);

define("ACCESS_TYPE_ARR", [
	ACCESS_TYPE_PRIVATE => "Private",
	ACCESS_TYPE_SHARED => "Shared",
]);