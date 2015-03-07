<?php

if(!defined('DirectAccess')) die();

$gameDB = GameDatabase::getInstance();


// build options for job selection

// output main template
$template_main = new Template("top_characters");

// if nothing has been selected before then select "All" entry
if(!isset($_POST['select_job']) or ($_POST['select_job'] == 0)) {
    $selected_flag = "selected";
} else {
    $selected_flag = "";
}
$template_jobOption = new Template("top_characters_selection_option_job");
$template_jobOption->addVariable('job_id', 0);
$template_jobOption->addVariable('job_name', getTranslation('all'));
$template_jobOption->addVariable('selected_flag', $selected_flag);
$template_main->addVariable('job_selection_entries', $template_jobOption->getTemplate());

// get all jobs from game database and output for selection
$query = $gameDB->query("AllJobs");
while($result = $gameDB->fetch($query)) {
    if(isset($_POST['select_job']) and ($_POST['select_job'] == $result['job_id'])) {
        $selected_flag = "selected";
    } else {
        $selected_flag = "";
    }

    $template_jobOption->resetVariables();
    $template_jobOption->addVariable('job_id', $result['job_id']);
    $template_jobOption->addVariable('job_name', $result['job_name']);
    $template_jobOption->addVariable('selected_flag', $selected_flag);
    $template_main->attachVariable('job_selection_entries', $template_jobOption->getTemplate());
}

// get Top 100 Characters
if(isset($_POST['select_job'])) {
    $selected_job = $_POST['select_job'];
} else {
    $selected_job = 0;
}


$template_main->addVariable('character_entries',  '');

$types = array('i','i');
$parameter = array($selected_job, $selected_job);
$query = $gameDB->query("Top100Character", $parameter, $types);
$i = 0;
while($result = $gameDB->fetch($query)) {
    $i++;
    if($i == 1) {
        $template_entry = new Template("top_characters_table_entry");
    } else {
        $template_entry->resetVariables();
    }
    $charInformation = new Character($result['char_id']);
    $template_entry->addVariable('num_char', $i);
    $template_entry->addVariable('char_text_tooltip', $charInformation->getTextTooltipTemplate());
    $template_entry->addVariable('char_level', $charInformation->getLevel());
    $template_entry->addVariable('guild_icon_text', $charInformation->getGuild()->getTextTooltipTemplate());
    $template_main->attachVariable('character_entries',  $template_entry->getTemplate());
}

// output main template
echo $template_main->getTemplate();

?>