<?php

if(!defined('DirectAccess')) die();

$gameDB = GameDatabase::getInstance();

if(!isset($_GET['id'])) {
    header('Location: index.php');
}

// get guild Information
$guild = new Guild($_GET['id']);
if(!$guild->isDataAvailable()) {
    header('Location: index.php');
}

$template_main = new Template("guild");
$template_main->addVariable('guild_name', $guild->getName());
$template_main->addVariable('leader_text_tooltip', $guild->getLeader()->getTextTooltipTemplate());
$template_main->addVariable('dungeon_name', $guild->getDungeonName());
$template_main->addVariable('num_member', $guild->getNumberOfMember());
$template_main->addVariable('avg_member_level', $guild->getAverageMemberLevel());

/*
 * member entries
 */
$template_main->addVariable('member_entries', "");

$types = array('i');
$parameter = array($_GET['id']);
$query = $gameDB->query("GuildMemberList", $parameter, $types);
$i = 0;
while($memberInfo = $gameDB->fetch($query)) {
    if($i == 0) {
        $template_member_entry = new Template('guild_member_entry');
    } else {
        $template_member_entry->resetVariables();
    }
    $i++;
    $character = new Character($memberInfo['char_id']);
    $template_member_entry->addVariable('num_char', $i);
    $template_member_entry->addVariable('char_text_tooltip', $character->getTextTooltipTemplate());
    $template_member_entry->addVariable('char_level', $character->getLevel());
    $template_main->attachVariable('member_entries', $template_member_entry->getTemplate());
}

// output main template
echo $template_main->getTemplate();

?>