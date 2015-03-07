<?php

if(!defined('DirectAccess')) die();


class SummonCard extends Item {

    protected $summon = null;

    /**
     * Constructor of current class
     * @param  Array $itemResourceInfo -> information from ItemResource
     * @param  Array $itemInfo -> information from Item
     * @param  Array $summon -> information from Summon/SummonResource
     */
    public function __construct($itemResourceInfo, $itemInfo, $summon) {
        $this->summon = $summon;
        parent::__construct($itemResourceInfo, $itemInfo);
    }

    /**
     * Prepares and returns item tooltip template
     *
     * @return String -> converted HTML-File
     */
    public function getTooltipTemplate() {
        $templateTooltip = new Template("class/item_tooltip_summon_card");
        $templateTooltip->addVariable('item_name_text', $this->getItemNameText());
        $templateTooltip->addVariable('pet_evo_level', $this->getSummon()->getEvoLevelText());
        $templateTooltip->addVariable('pet_tier', $this->getSummon()->getTierText());
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
        if($this->getSummon()->getOverbreed() > 0) {
            $itemNameText .= "+".$this->getSummon()->getOverbreed()." ";
        }
        $itemNameText .= $this->name." ".getTranslation('lv').$this->getSummon()->getLevel();

        return $itemNameText;
    }

    public function getSummon() {
        if(is_null($this->summon)) {
            $this->summon = new Summon($this->id, TRUE);
        }
        return $this->summon;
    }

}

?>