<?php

namespace octoapi\action\product;


class main extends \octoapi\core\com\intf\standard {
    //------------------------------------------------------------------------------------------------------------------
    public function get(): \octoapi\action\product\get {
        return \octoapi\action\product\get::make();
    }
    //------------------------------------------------------------------------------------------------------------------
    public function post(): \octoapi\action\product\post {
        return \octoapi\action\product\post::make();
    }
    //------------------------------------------------------------------------------------------------------------------
    public function put(): \octoapi\action\product\put {
        return \octoapi\action\product\put::make();
    }
    //------------------------------------------------------------------------------------------------------------------
    public function delete(): \octoapi\action\product\delete {
        return \octoapi\action\product\delete::make();
    }
    //------------------------------------------------------------------------------------------------------------------

}