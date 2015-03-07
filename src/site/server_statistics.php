<?php

if(!defined('DirectAccess')) die();

$gameDB = GameDatabase::getInstance();


$template_main = new Template("server_statistics");


if($serverStatistics = GameDatabase::getInstance()->queryFirst("ServerStatistics")) {
    $template_main->addVariable('total_accounts', $serverStatistics['total_accounts']);
    $template_main->addVariable('total_character', $serverStatistics['total_character']);
    $template_main->addVariable('online_player', $serverStatistics['online_player']);
    $template_main->addVariable('active_character', $serverStatistics['active_character']);
    $template_main->addVariable('total_guilds', $serverStatistics['total_guilds']);
    $template_main->addVariable('total_alliance', $serverStatistics['total_alliance']);
    $template_main->addVariable('current_parties', $serverStatistics['current_parties']);
}

if($serverStatistics = GameDatabase::getInstance()->queryFirst("ServerStatisticsCharacter")) {
    $template_main->addVariable('total_gaia_character', $serverStatistics['total_gaia_character']);
    $template_main->addVariable('total_deva_character', $serverStatistics['total_deva_character']);
    $template_main->addVariable('total_asura_character', $serverStatistics['total_asura_character']);
    $template_main->addVariable('total_r7_character', $serverStatistics['total_r7_character']);
    $template_main->addVariable('total_r6_character', $serverStatistics['total_r6_character']);
    $template_main->addVariable('total_r5_character', $serverStatistics['total_r5_character']);
    $template_main->addVariable('total_r4_character', $serverStatistics['total_r4_character']);
    $template_main->addVariable('total_r3_character', $serverStatistics['total_r3_character']);
    $template_main->addVariable('total_r2_character', $serverStatistics['total_r2_character']);
    $template_main->addVariable('total_r1_character', $serverStatistics['total_r1_character']);
}

if($serverStatistics = GameDatabase::getInstance()->queryFirst("ServerStatisticsPets")) {
    $template_main->addVariable('total_tamed_pets', $serverStatistics['total_tamed_pets']);
    $template_main->addVariable('total_pets_evo3', $serverStatistics['total_pets_evo3']);
    $template_main->addVariable('total_pets_evo2', $serverStatistics['total_pets_evo2']);
    $template_main->addVariable('total_pets_evo1', $serverStatistics['total_pets_evo1']);
    $template_main->addVariable('total_pets_tier6', $serverStatistics['total_pets_tier6']);
    $template_main->addVariable('total_pets_tier5', $serverStatistics['total_pets_tier5']);
    $template_main->addVariable('total_pets_tier4', $serverStatistics['total_pets_tier4']);
    $template_main->addVariable('total_pets_tier3', $serverStatistics['total_pets_tier3']);
    $template_main->addVariable('total_pets_tier2', $serverStatistics['total_pets_tier2']);
    $template_main->addVariable('total_pets_tier1', $serverStatistics['total_pets_tier1']);
}

// output main template
echo $template_main->getTemplate();

?>