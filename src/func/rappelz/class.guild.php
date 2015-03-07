<?php

if(!defined('DirectAccess')) die();


class Guild {
    private $dataAvailable = FALSE;

    protected $id = -1;
    protected $name = "";
    protected $dungeonName = "";

    protected $leaderID = -1;
    protected $leader = null;
    // TODO: add guild icon

    protected $numberOfMember = -1;
    protected $averageMemberLevel = -1;

    /**
     * Constructor of current class
     */
    public function __construct($guildID) {
        $this->id = $guildID;
        $this->dataAvailable = $this->loadGuildInformation();
    }

    /**
     * Loads guild information from database
     * @return Boolean -> guild data found
     */
    private function loadGuildInformation() {
        $types = array('i');
        $parameter = array($this->id);
        if($guildInfo = GameDatabase::getInstance()->queryFirst("GuildInformation", $parameter, $types)) {
            // set guild data
            $this->name = $guildInfo['guild_name'];
            $this->leaderID = $guildInfo['guild_leader'];
            $this->dungeonName = convertString($guildInfo['dungeon_name']);
            $this->numberOfMember = $guildInfo['number_member'];
            $this->averageMemberLevel = $guildInfo['avg_member_level'];
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * Prepares and returns guild icon templatet
     * @return String -> converted HTML-File
     */
    public function getTextTooltipTemplate() {
        if($this->dataAvailable) {
            $templateTextTooltip = new Template("class/guild_text_tooltip");
            $templateTextTooltip->addVariable('guild_id', $this->id);
            $templateTextTooltip->addVariable('guild_name', $this->name);
            $templateTextTooltip->addVariable('leader_name', $this->getLeader()->getName());
            return $templateTextTooltip->getTemplate();
        } else {
            return "";
        }
    }

    public function isDataAvailable() {
        return $this->dataAvailable;
    }

    public function getName() {
        return $this->name;
    }

    public function getDungeonName() {
        return $this->dungeonName;
    }

    public function getLeader() {
        if(is_null($this->leader)) {
            $this->leader = new Character($this->leaderID);
        }
        return $this->leader;
    }

    public function getNumberOfMember() {
        return $this->numberOfMember;
    }

    public function getAverageMemberLevel() {
        return $this->averageMemberLevel;
    }
}

?>