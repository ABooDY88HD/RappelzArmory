<?php

if(!defined('DirectAccess')) die();


class Equipment extends Item {

    /**
     * Constructor of current class
     * @param  Array $itemResourceInfo -> information from ItemResource
     * @param  Array $itemInfo -> information from Item
     */
    public function __construct($itemResourceInfo, $itemInfo) {
        parent::__construct($itemResourceInfo, $itemInfo);
    }

    /**
     * Prepares and returns item tooltip template
     *
     * @return String -> converted HTML-File
     */
    public function getTooltipTemplate() {
        $templateTooltip = new Template("class/item_tooltip_equipment");
        $templateTooltip->addVariable('item_name_text', $this->getItemNameText());
        //$templateTooltip->addVariable('item_name_text', $this->tooltipString->getValue());
        $templateTooltip->addVariable('item_rank', $this->rank);
        return $templateTooltip->getTemplate();
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
        $itemNameText .= $this->name." ".getTranslation('lv').$this->level;
        return $itemNameText;
    }

}

?>