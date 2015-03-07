<?php

if(!defined('DirectAccess')) die();


class Character {
    private $dataAvailable = FALSE;

    protected $id = -1;
    protected $name = "";
    protected $level = 1;
    protected $job = null;

    // object guild will only created if it is used
    protected $guildID = -1;
    protected $guild = null;

    /**
     * Constructor of current class
     * @param  String $pTemplateName -> Name of html template
     */
    public function __construct($characterID) {
        $this->id = $characterID;
        $this->dataAvailable = $this->loadCharacterInformation();
    }

    /**
     * Loads item information from database
     * @return Boolean -> item data found
     */
    private function loadCharacterInformation() {
        $types = array('i');
        $parameter = array($this->id);
        if($characterInfo = GameDatabase::getInstance()->queryFirst("CharacterInformation", $parameter, $types)) {
            // set item data
            $this->name = $characterInfo['char_name'];
            $this->level = $characterInfo['char_level'];
            $this->guildID = $characterInfo['guild_id'];
            $this->job = new Job($characterInfo['job_id']);
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * Prepares and returns character icon template with tooltip
     *
     * @return String -> converted HTML-File
     */
    public function getTextTooltipTemplate() {
        $templateTextTooltip = new Template("class/character_text_tooltip");
        $templateTextTooltip->addVariable('job_icon', $this->job->getIconTemplate());
        $templateTextTooltip->addVariable('char_id', $this->id);
        $templateTextTooltip->addVariable('char_name', $this->name);
        $templateTextTooltip->addVariable('char_level', $this->level);
        $templateTextTooltip->addVariable('guild_name', $this->getGuild()->getName());
        return $templateTextTooltip->getTemplate();
    }

    public function isDataAvailable() {
        return $this->dataAvailable;
    }

    public function getName() {
        return $this->name;
    }

    public function getLevel() {
        return $this->level;
    }

    public function getGuild() {
        if(is_null($this->guild)) {
            $this->guild = new Guild($this->guildID);
        }
        return $this->guild;
    }

}

?>