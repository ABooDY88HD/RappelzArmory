<?php

if(!defined('DirectAccess')) die();


class Template {

    private $templateName;

    private $templateVariables = Array();

    private $lastPreparedTemplate = null;

    /**
     * Constructor of current class
     * @param  String $pTemplateName -> Name of html template
     */
    public function __construct($pTemplateName) {
        // check if template available
        if(!file_exists('template/'.$pTemplateName.'.html')) {
            write_errorlog("Template does not exist: ".$pTemplateName.".html",2,"class.template.php");
        } else {
            $this->templateName = $pTemplateName;
        }
    }

    /**
     * Adds given variable to template variables
     * @param  String $variableName -> Variable name
     * @param  String $variableValue -> Value of variable
     */
    public function addVariable($variableName, $variableValue) {
        $this->templateVariables[$variableName] = $variableValue;
    }

    /**
     * Attachs given variable to template variables
     * @param  String $variableName -> Variable name
     * @param  String $variableValue -> Value of variable
     */
    public function attachVariable($variableName, $variableValue) {
        if(array_key_exists($variableName, $this->templateVariables)) {
            $this->templateVariables[$variableName] .= $variableValue;
        } else {
            $this->templateVariables[$variableName] = $variableValue;
        }
    }

    /**
     * Adds given list of variables to template variables
     * @param  Array $arrayVariables -> Array of variables
     */
    public function addArrayVariables($arrayVariables) {
        $this->templateVariables = array_merge($this->templateVariables, $arrayVariables);
    }

    /**
     * Reset current variables in $templateVariables
     */
    public function resetVariables() {
        $this->templateVariables = Array();
    }

    /**
     * Prepare template, get template from file and replace static text
     */
    private function prepareTemplate() {
        global $language_array;
        $this->lastPreparedTemplate = file_get_contents('template/'.$this->templateName.'.html');

        // replace static text
        foreach($language_array as $key => $val) {
            $this->lastPreparedTemplate = str_replace('%'.$key.'%', $val, $this->lastPreparedTemplate);
        }
    }

    /**
     * Gets template from file and returns it
     * replace static text and variable in template
     *
     * @return String -> converted HTML-File
     */
    public function getTemplate() {
        if($this->lastPreparedTemplate == null) {
            $this->prepareTemplate();
        }
        $template = $this->lastPreparedTemplate;
        // replace variable
        foreach($this->templateVariables as $key => $val) {
            $template = str_replace('$'.$key.'$', $val, $template);
        }
        return $template;
    }

}

?>