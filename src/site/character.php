<?php

if(!defined('DirectAccess')) die();

$gameDB = GameDatabase::getInstance();

if(!isset($_GET['id'])) {
    header('Location: index.php');
}

// get character Information
$types = array('i');
$parameter = array($_GET['id']);
$query = $gameDB->query("CharacterInformation", $parameter, $types);
if(!($charInfo = $gameDB->fetch($query))) {
    header('Location: index.php');
}

$template_main = new Template("character");
$template_main->addVariable('char_name', $charInfo['char_name']);
$template_main->addVariable('char_level', $charInfo['char_level']);
$template_main->addVariable('job_icon_text', (new Job($charInfo['job_id']))->getIconTextTemplate());
$template_main->addVariable('job_level', $charInfo['job_level']);
$template_main->addVariable('guild_text_tooltip', (new Guild($charInfo['guild_id']))->getTextTooltipTemplate());
$template_main->addVariable('total_exp', $charInfo['total_exp']);
$template_main->addVariable('char_created', $charInfo['char_created']->format('Y-m-d H:i:s'));
if($charInfo['char_logged_in'] > $charInfo['char_logged_out']) {
    $template_main->addVariable('last_seen', getTranslation("online"));
} else {
    $template_main->addVariable('last_seen', $charInfo['char_logged_out']->format('Y-m-d H:i:s'));
}

/*
 * equipment
 */

$template_equip_none = new Template("character_equipment_none");

// get current character equipment
$types = array('i','i');
$parameter = array($_GET['id'], 0);
$query = $gameDB->query("CurrentEquipment", $parameter, $types);
$last_wear_info = -1;
for($i=0; $i<=23; $i++) {
    if($last_wear_info < $i) {
        $equip = $gameDB->fetch($query);
        if($equip != FALSE) {
            $last_wear_info = $equip['item_wear_info'];
        } else {
            $last_wear_info = 24;
        }
    }
    // wear_info 13 does not exists, skip it
    if($i != 13) {
        if(($last_wear_info <= 23) and ($i == $equip['item_wear_info'])) {
            $item = Item::newInstance($equip['item_id']);
            $template_main->addVariable('wear_slot-'.$i, $item->getIconTooltipTemplate());
        } else {
            $template_equip_none->resetVariables();
            $template_equip_none->addVariable('wear_slot_icon', "wear_slot_".$i);
            if($i == 10) {
                $template_equip_none->addVariable('wear_slot_icon', "wear_slot_9");
            }
            $template_equip_none->addVariable('slot_name', getTranslation("equip_slot_name-".$i));
            $template_main->addVariable('wear_slot-'.$i, $template_equip_none->getTemplate());
        }
    }
}

/*
 * belt
 * Attention! currently it will not be checked how much slots the belt got (1 assumed)
 */
for($i=0; $i<=5; $i++) {
    // card in belt?
    if($charInfo['belt_slot-'.$i] != 0) {
        // get belt slot information
        $item = Item::newInstance($charInfo['belt_slot-'.$i]);
        $template_main->addVariable('belt_slot-'.$i, $item->getIconTooltipTemplate());
    } else {
        $template_equip_none->resetVariables();
        // check how many slots the belt got (1 assumed)
        if($i < 1) {
            $template_equip_none->addVariable('slot_name', getTranslation("empty_slot"));
            $template_equip_none->addVariable('wear_slot_icon', "inventory_empty_slot");
        } else {
            $template_equip_none->addVariable('slot_name', getTranslation("locked_slot"));
            $template_equip_none->addVariable('wear_slot_icon', "inventory_no_slot");
        }
        $template_main->addVariable('belt_slot-'.$i, $template_equip_none->getTemplate());
    }
}

/*
 * Pets
 * Attention! currently it will not be checked how much slots can be equipped at pet (2 assumed)
 */
$template_pet_entry = new Template("character_summon_entry");
$template_main->addVariable('pet_entries', "");
for($i=0; $i<=5; $i++) {
    if($charInfo['summon_slot-'.$i] > 0) {
        $template_pet_entry->resetVariables();
        // get pet information
        $summon = new Summon($charInfo['summon_slot-'.$i]);
        if($summon->isDataAvailable()) {
            $template_pet_entry->addVariable('summon_formation', $i+1);
            $template_pet_entry->addVariable('summon_icon_text', $summon->getTextTooltipTemplate());
            $template_pet_entry->addVariable('summon_level_overbreed', $summon->getLevelOverbreed());
            $template_pet_entry->addVariable('summon_tier', $summon->getTierText());
            // get current pet equipment
            $pet_equipment = "";
            $types = array('i','i');
            $parameter = array($_GET['id'], $charInfo['summon_slot-'.$i]);
            $query = $gameDB->query("CurrentEquipment", $parameter, $types);
            $last_wear_info = -1;
            for($i=0; $i<=5; $i++) {
                if($last_wear_info < $i) {
                    $equip = $gameDB->fetch($query);
                    if($equip != FALSE) {
                        $last_wear_info = $equip['item_wear_info'];
                    } else {
                        $last_wear_info = 6;
                    }
                }
                if(($last_wear_info <= 5) and ($i == $equip['item_wear_info'])) {
                    $item = Item::newInstance($equip['item_id']);
                    $template_pet_entry->addVariable('wear_slot-'.$i, $item->getIconTooltipTemplate());
                } else {
                    $template_equip_none->resetVariables();
                    // check how many slots can be equipped (2 assumed)
                    if($i < 2) {
                        $template_equip_none->addVariable('slot_name', getTranslation("empty_slot"));
                        $template_equip_none->addVariable('wear_slot_icon', "inventory_empty_slot");
                    } else {
                        $template_equip_none->addVariable('slot_name', getTranslation("locked_slot"));
                        $template_equip_none->addVariable('wear_slot_icon', "inventory_no_slot");
                    }
                    $template_pet_entry->addVariable('wear_slot-'.$i, $template_equip_none->getTemplate());
                }
            }
            // add template
            $template_main->attachVariable('pet_entries', $template_pet_entry->getTemplate());
        }
    }
}


/*
 * job entries
 */
$template_main->addVariable('job_entries', "");
for($i=3; $i>=0; $i--) {
    if($i == 3) {
        $jobId = $charInfo['job_id'];
        $jobLevel = $charInfo['job_level'];
        $template_job_entry = new Template("character_job_entry");
    } else {
        $jobId = $charInfo['job_id-'.$i];
        $jobLevel = $charInfo['job_level-'.$i];
        $template_job_entry->resetVariables();
    }
    if($jobId > 0) {
        // get job information
        $job = new Job($jobId);
        if($job->isDataAvailable()) {
            $template_job_entry->addVariable('job_class', $job->getDepthText());
            $template_job_entry->addVariable('job_icon_text', $job->getIconTextTemplate());
            $template_job_entry->addVariable('job_level', $jobLevel);
            $template_job_entry->addVariable('job_type', $job->getTypeText());
            $template_main->attachVariable('job_entries', $template_job_entry->getTemplate());
        }
    }
}

// output main template
echo $template_main->getTemplate();

?>