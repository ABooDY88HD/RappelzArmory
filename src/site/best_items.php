<?php

if(!defined('DirectAccess')) die();

$gameDB = GameDatabase::getInstance();

// prepare main template
$template_main = new Template("best_items");


/*
 * Weapons
 */
$q_bestWeapons = $gameDB->query("BestWeapons");
$i = 0;
while($weapon = $gameDB->fetch($q_bestWeapons)) {
    $i++;
    if($i == 1) {
        $template_entry = new Template("best_items_entry");
    } else {
        $template_entry->resetVariables();
    }
    $itemInformation = Item::newInstance($weapon['item_id']);
    $template_entry->addVariable('num_weapon', $i);
    $template_entry->addVariable('weapon_text_tooltip', $itemInformation->getTextTooltipTemplate());
    $template_entry->addVariable('char_text_tooltip', $itemInformation->getOwner()->getTextTooltipTemplate());
    $template_main->attachVariable('best_weapons_entries', $template_entry->getTemplate());
}

/*
 * equipment
 */
$q_bestEquip = $gameDB->query("BestEquipment");
$i = 0;
while($equip = $gameDB->fetch($q_bestEquip)) {
    $i++;
    if($i == 1) {
        $template_entry = new Template("best_items_entry");
    } else {
        $template_entry->resetVariables();
    }
    $itemInformation = Item::newInstance($equip['item_id']);
    $template_entry->addVariable('num_weapon', $i);
    $template_entry->addVariable('weapon_text_tooltip', $itemInformation->getTextTooltipTemplate());
    $template_entry->addVariable('char_text_tooltip', $itemInformation->getOwner()->getTextTooltipTemplate());
    $template_main->attachVariable('best_equipment_entries', $template_entry->getTemplate());
}

/*
 * equipment
 */
$q_bestSkillcards = $gameDB->query("BestSkillcards");
$i = 0;
while($skillcard = $gameDB->fetch($q_bestSkillcards)) {
    $i++;
    if($i == 1) {
        $template_entry = new Template("best_items_entry");
    } else {
        $template_entry->resetVariables();
    }
    $itemInformation = Item::newInstance($skillcard['item_id']);
    $template_entry->addVariable('num_weapon', $i);
    $template_entry->addVariable('weapon_text_tooltip', $itemInformation->getTextTooltipTemplate());
    $template_entry->addVariable('char_text_tooltip', $itemInformation->getOwner()->getTextTooltipTemplate());
    $template_main->attachVariable('best_skillcard_entries', $template_entry->getTemplate());
}

// output template
echo $template_main->getTemplate();

?>