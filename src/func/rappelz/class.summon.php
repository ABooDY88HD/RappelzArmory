<?php

if(!defined('DirectAccess')) die();


class Summon {
    private $dataAvailable = FALSE;

    private $id = -1;
    private $name = "";
    private $typeName = "";
    private $iconFile = "common_mark_icon_unknown_middle";
    private $illustFile = "game_image_card_empty";
    private $tier = -1;
    private $evoLevel = 0;
    private $level = 0;
    private $overbreed = 0;

    // object item will only created if it is used
    private $itemID = -1;
    private $item = null;

    // object owner will only created if it is used
    private $ownerID = -1;
    private $owner = null;

    /**
     * Constructor of current class
     */
    public function __construct($summonID, $isItemID=FALSE) {
        // -1 is an invalid id
        if($summonID == -1) {
            return;
        }
        // get summon data from database
        if(!$isItemID) {
            $this->id = $summonID;
        } else {
            $this->itemID = $summonID;
        }
        $this->dataAvailable = $this->loadSummonInformation();
    }

    /**
     * Loads summon information from database
     * @return Boolean -> summon data found
     */
    private function loadSummonInformation() {
        $types = array('i','i','i','i');
        $parameter = array($this->id,$this->id,$this->itemID,$this->itemID);
        if($summonInfo = GameDatabase::getInstance()->queryFirst("SummonInformation", $parameter, $types)) {
            // set summon data
            $this->id = $summonInfo['summon_id'];
            $this->itemID = $summonInfo['summon_itemid'];
            $this->ownerID = $summonInfo['summon_owner'];
            $this->name = $summonInfo['summon_name'];
            $this->typeName = convertString($summonInfo['summon_type_name']);
            $this->tier = $summonInfo['summon_tier'];
            $this->evoLevel = $summonInfo['summon_evo_level'];
            $this->level = $summonInfo['summon_level'];
            $this->overbreed = $summonInfo['summon_overbreed'];
            $this->iconFile = $summonInfo['summon_icon_file'];
            $this->illustFile = $summonInfo['summon_illust_file'];
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * Prepares and returns summon text template with tooltip
     *
     * @return String -> converted HTML-File
     */
    public function getTextTooltipTemplate() {
        $templateTextTooltip = new Template("class/summon_text_tooltip");
        $templateTextTooltip->addVariable('summon_icon', $this->iconFile);
        $templateTextTooltip->addVariable('summon_name', $this->name);
        $templateTextTooltip->addVariable('summon_type_name_text', $this->getSummonTypeNameText());
        $templateTextTooltip->addVariable('summon_evo_level', $this->getEvoLevelText());
        $templateTextTooltip->addVariable('summon_tier', $this->getTierText());
        return $templateTextTooltip->getTemplate();
    }

    /**
     * Prepares and returns summon name
     *
     * @return String -> Summon Type text
     */
    public function getSummonTypeNameText() {
        $summonTypeNameText = "";
        if($this->overbreed > 0) {
            $summonTypeNameText .= "+".$this->overbreed." ";
        }
        $summonTypeNameText .= $this->typeName." ".getTranslation('lv').$this->level;

        return $summonTypeNameText;
    }

    public function isDataAvailable() {
        return $this->dataAvailable;
    }

    public function getItem() {
        if(is_null($this->item)) {
            $this->item = new Character($this->itemID);
        }
        return $this->item;
    }

    public function getOwner() {
        if(is_null($this->owner)) {
            $this->owner = new Character($this->ownerID);
        }
        return $this->owner;
    }

    public function getLevelOverbreed() {
        if($this->overbreed > 0) {
            return $this->level."+".$this->overbreed;
        } else {
            return $this->level;
        }
    }

    public function getEvoLevelText() {
        return getTranslation('pet_evo_level-'.$this->evoLevel);
    }

    public function getTierText() {
        return getTranslation('pet_tier-'.$this->tier);
    }

    public function getOverbreed() {
        return $this->overbreed;
    }

    public function getLevel() {
        return $this->level;
    }

}

?>