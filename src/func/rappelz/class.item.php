<?php

if(!defined('DirectAccess')) die();


class Item {
    private $dataAvailable = FALSE;

    protected $id = -1;
    protected $itemCode = -1;
    protected $name = "";
    protected $iconFile = "common_mark_icon_unknown_middle";
    protected $rank = 0;
    protected $level = 1;
    protected $enhance = 0;
    protected $numberSockets = 0;
    protected $sockets = array();
    protected $elementalEffectPatk = 0;
    protected $elementalEffectMatk = 0;

    protected $itemType = 0;
    protected $itemGroup = 0;
    protected $itemWearType = 0;

    // object owner will only created if it is used
    protected $ownerID = -1;
    protected $owner = null;

    protected $tooltipString = null;

    /**
     * Constructor of current class
     * @param  Array $itemResourceInfo -> information from ItemResource
     * @param  Array $itemInfo -> information from Item
     */
    protected function __construct($itemResourceInfo, $itemInfo) {
        // get item data from database
        $this->itemCode = $itemInfo['item_code'];
        $this->name = convertString($itemResourceInfo['item_name']);
        $this->iconFile = $itemResourceInfo['item_icon_file'];
        $this->rank = $itemResourceInfo['item_rank'];
        $this->numberSockets = $itemResourceInfo['item_num_socket'];
        $this->itemType = $itemResourceInfo['item_type'];
        $this->itemGroup = $itemResourceInfo['item_group'];
        $this->itemWearType = $itemResourceInfo['item_wear_type'];
        $this->tooltipString = new StringResource($itemResourceInfo['item_tooltip_id']);

        if(isset($itemInfo['item_id'])) {
            $this->id = $itemInfo['item_id'];
            $this->ownerID = $itemInfo['item_owner'];
            $this->level = $itemInfo['item_level'];
            $this->enhance = $itemInfo['item_enhance'];
            $this->sockets[0] = $itemInfo['item_socket-0'];
            $this->sockets[1] = $itemInfo['item_socket-1'];
            $this->sockets[2] = $itemInfo['item_socket-2'];
            $this->sockets[3] = $itemInfo['item_socket-3'];
            $this->elementalEffectPatk = $itemInfo['item_elemental_effect_patk'];
            $this->elementalEffectMatk = $itemInfo['item_elemental_effect_matk'];
        }
    }

    /**
     * Create and return new instance of item with child class
     * @param  Integer $pItemID -> item ID
     * @param  Boolean $isResource (optional) -> item from resource
     */
    public static function newInstance($itemID, $isResource=FALSE) {
        if(!$isResource) {
            $types = array('i');
            $parameter = array($itemID);
            if($itemInfo = GameDatabase::getInstance()->queryFirst("ItemInformation", $parameter, $types)) {
                $itemCode = $itemInfo['item_code'];
            } else {
                return FALSE;
            }
        } else {
            $itemCode = $itemID;
            $itemID = -1;
        }
        $types = array('i');
        $parameter = array($itemCode);
        if($itemResourceInfo = GameDatabase::getInstance()->queryFirst("ItemResourceInformation", $parameter, $types)) {
            $itemResourceInfo['item_code'] = $itemCode;
            $itemInfo['item_id'] = $itemID;
            // create object depending on data

            // check if item is a summon card
            if(($itemResourceInfo['item_summon_id'] > 0) and ($itemID != -1)) {
                $summon = new Summon($itemID, TRUE);
                if($summon->isDataAvailable()) {
                    return new SummonCard($itemResourceInfo, $itemInfo, $summon);
                }
            }

            // check if item is a equipment part
            if(($itemResourceInfo['item_type'] == 1) and ($itemResourceInfo['item_group'] >= 1 and $itemResourceInfo['item_group'] <= 9)) {
                return new Equipment($itemResourceInfo, $itemInfo);
            }

            // check if item is a skill card
            if($itemResourceInfo['item_wear_type'] == 100) {
                return new SkillCard($itemResourceInfo, $itemInfo);
            }

            return new Item($itemResourceInfo, $itemInfo);

        } else {
            return FALSE;
        }
    }

    /**
     * Prepares and returns item tooltip template
     *
     * @return String -> converted HTML-File
     */
    public function getTooltipTemplate() {
        $templateTooltip = new Template("class/item_tooltip");
        $templateTooltip->addVariable('item_name_text', $this->getItemNameText());
        //$templateTooltip->addVariable('item_name_text', $this->tooltipString->getValue());
        return $templateTooltip->getTemplate();
    }

    /**
     * Prepares and returns item icon template with tooltip
     *
     * @return String -> converted HTML-File
     */
    public function getIconTooltipTemplate() {
        $templateIconTooltip = new Template("class/item_icon_tooltip");
        $templateIconTooltip->addVariable('item_icon', $this->iconFile);
        $templateIconTooltip->addVariable('item_tooltip', $this->getTooltipTemplate());
        return $templateIconTooltip->getTemplate();
    }

    /**
     * Prepares and returns item text template with tooltip
     *
     * @return String -> converted HTML-File
     */
    public function getTextTooltipTemplate() {
        $templateTextTooltip = new Template("class/item_text_tooltip");
        $templateTextTooltip->addVariable('item_icon', $this->iconFile);
        $templateTextTooltip->addVariable('item_name_text', $this->getItemNameText());
        $templateTextTooltip->addVariable('item_tooltip', $this->getTooltipTemplate());
        return $templateTextTooltip->getTemplate();
    }

    public function isDataAvailable() {
        return $this->dataAvailable;
    }

    public function getItemNameText() {
        return $this->name;
    }

    public function getOwner() {
        if(is_null($this->owner)) {
            $this->owner = new Character($this->ownerID);
        }
        return $this->owner;
    }

}

?>