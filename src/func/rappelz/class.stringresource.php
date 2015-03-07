<?php

if(!defined('DirectAccess')) die();


class StringResource {

    private $id = -1;
    private $value = "empty_string:0000000000";

    /**
     * Constructor of current class
     */
    public function __construct($string) {
        if(is_numeric($string)) {
            $this->id = $string;
            $this->loadStringResourceInformation();
        } else {
            $this->value = $string;
        }
        $this->prepareValue();
    }

    /**
     * Loads StringResource information from database
     */
    private function loadStringResourceInformation() {
        $types = array('i');
        $parameter = array($this->id);
        if($StringResourceInfo = GameDatabase::getInstance()->queryFirst("StringResourceInformation", $parameter, $types)) {
            $this->value = $StringResourceInfo['string_value'];
        } else {
            $this->value = "empty_string:".str_pad($stringID, 10, '0', STR_PAD_LEFT);
        }
    }

    /**
     * Prepares current value
     * Replaces <size: tags to html <span tags
     */
    private function prepareValue() {
        // convert <size: tags
        if(strpos($this->value, '<size:') !== false) {
            $this->value = preg_replace('#(.*)<size:(.*)>(.*)#Uis', '\1<span style="font-size: \2px">\3', $this->value, 1);
            $this->value = preg_replace('#(.*)<size:(.*)>(.*)#Uis', '\1</span><span style="font-size: \2px">\3', $this->value);
            $this->value .= "</span>";
        }
    }

    public function replaceVariable($variableName, $variableValue) {
        $this->value = str_replace('#@'.$variableName.'@#', $variableValue, $this->value);
    }

    public function getValue() {
        return $this->value;
    }
}

?>