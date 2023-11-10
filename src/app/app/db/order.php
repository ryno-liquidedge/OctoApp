<?php

namespace LiquidedgeApp\Octoapp\app\app\db;

/**
 * Class order
 * @package app\db
 * @author Ryno Van Zyl
 */

class order{
    private $dbobj_current = false;
    private $dbobj_prev = false;
    private $table = false;
    private $peer_arr = [];
    
    //--------------------------------------------------------------------------------
    // constructor
    //--------------------------------------------------------------------------------
    public function __construct($dbobj_current, $dbobj_prev) {
        
        if(!($dbobj_current instanceof \com\db\table || $dbobj_current instanceof \com\db\row)){ 
            \LiquidedgeApp\Octoapp\app\app\error\error::create("The current db object is not an instance of a com.db.table obj");
        }else{
            $this->dbobj_current = $dbobj_current->splat($dbobj_current);
            $this->dbobj_prev = $dbobj_prev ? $dbobj_prev->splat($dbobj_prev) : false;
            $this->table = $this->dbobj_current->db->name;
        }
    }
    //--------------------------------------------------------------------------------
    /**
     * Gets a list of peers that will be adjusted with a new order value
     * @param type $mixed
     * @param type $options
     */
    public function set_peers_fromdb($mixed, $options = []) {
        $options_arr = array_merge([
            "multiple" => true,
        ], $options);
        $this->peer_arr = \core::$db->{$this->table}->get_fromdb($mixed, $options_arr);
    }
    //--------------------------------------------------------------------------------
    /**
     * Trigger the reordering
     * @param type $order_column_name
     */
    public function trigger($order_column_name) {
        // init
        $i = 0;

        // our first item is locked in first place
        if (!$this->dbobj_prev){
            $this->dbobj_current->{$order_column_name} = 1;
            $this->dbobj_current->update();
            $i++;
            
            $this->dbobj_prev = $this->dbobj_current;
        }

        // re-order all items
        foreach ($this->peer_arr as $obj_current_item) {
            // init
            $i++;

            // skip our target item
            if ($obj_current_item->id == $this->dbobj_current->id)
                continue;

            // set order
            $obj_current_item->{$order_column_name} = $i;

            // check if this is where we need to bring in our target item
            if ($this->dbobj_prev && $obj_current_item->id == $this->dbobj_prev->id) {
                $i++;
                $this->dbobj_current->{$order_column_name} = $i;
                $this->dbobj_current->update();
            }

            // save and increase order for next item
            $obj_current_item->update();
        }
    }
    //--------------------------------------------------------------------------------
    // static
    //--------------------------------------------------------------------------------
    public static function make($dbobj_current, $dbobj_prev) {
        return new order($dbobj_current, $dbobj_prev);
    }
    //--------------------------------------------------------------------------------
}
