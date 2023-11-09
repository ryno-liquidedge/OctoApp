<?php

namespace octoapi\action\company\factory;


class main extends \octoapi\core\com\intf\standard {
    //------------------------------------------------------------------------------------------------------------------
    public function get(): \octoapi\action\company\get {
        return \octoapi\action\company\get::make();
    }
    //------------------------------------------------------------------------------------------------------------------

}