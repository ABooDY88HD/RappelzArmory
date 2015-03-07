<?php

if(!defined('DirectAccess')) die();


class Job {

    private $dataAvailable = FALSE;

    protected $id = -1;
    protected $name = "unknown";
    protected $iconFile = "common_mark_icon_unknown_mini";

    /**
     * job depth
     *      0 = Basic
     *      1 = First job
     *      2 = Second Job
     *      3 = Master Class
     * @var Integer
     */
    protected $depth = 0;

    /**
     * job type
     *      1 = Warrior
     *      2 = Hunter
     *      3 = Mage
     *      4 = Summoner
     * @var Integer
     */
    protected $type = 1;

    /**
     * Constructor of current class
     */
    public function __construct($jobID) {
        $this->id = $jobID;
        $this->dataAvailable = $this->loadJobInformation();
    }

    /**
     * Loads job information from database
     * @return Boolean -> job data found
     */
    private function loadJobInformation() {
        $types = array('i');
        $parameter = array($this->id);
        if($jobInfo = GameDatabase::getInstance()->queryFirst("JobInformation", $parameter, $types)) {
            // set job data
            $this->name = $jobInfo['job_name'];
            $this->iconFile = $jobInfo['job_icon_file'];
            $this->depth = $jobInfo['job_depth'];
            $this->type = $jobInfo['job_class'];
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * Prepares and returns job icon template
     * @return String -> converted HTML-File
     */
    public function getIconTemplate() {
        $templateIcon = new Template("class/job_icon");
        $templateIcon->addVariable('job_name', $this->name);
        $templateIcon->addVariable('job_icon_file', $this->iconFile);
        return $templateIcon->getTemplate();
    }

    /**
     * Prepares and returns job icon template with job name text
     * @return String -> converted HTML-File
     */
    public function getIconTextTemplate() {
        $templateIconText = new Template("class/job_icon_text");
        $templateIconText->addVariable('job_name', $this->name);
        $templateIconText->addVariable('job_icon_file', $this->iconFile);
        return $templateIconText->getTemplate();
    }

    public function isDataAvailable() {
        return $this->dataAvailable;
    }

    public function getDepthText() {
        return getTranslation("job_depth-".$this->depth);
    }

    public function getTypeText() {
        return getTranslation("job_type-".$this->type);
    }
}

?>