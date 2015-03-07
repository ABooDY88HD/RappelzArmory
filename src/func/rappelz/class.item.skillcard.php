<?php

if(!defined('DirectAccess')) die();


class SkillCard extends Item {

    /**
     * Constructor of current class
     * @param  Array $itemResourceInfo -> information from ItemResource
     * @param  Array $itemInfo -> information from Item
     */
    public function __construct($itemResourceInfo, $itemInfo) {
        parent::__construct($itemResourceInfo, $itemInfo);
    }

    /**
     * @override
     * Prepares and returns item name
     *
     * @return String -> Item name
     */
    public function getItemNameText() {
        $itemNameText = "";
        if($this->enhance > 0) {
            $itemNameText .= "+".$this->enhance." ";
        }
        $itemNameText .= $this->name;
        return $itemNameText;
    }

}

?>