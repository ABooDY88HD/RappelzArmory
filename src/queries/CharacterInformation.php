<?php

$query_mssql = "
SELECT
    char.name as 'char_name',
    char.lv as 'char_level',
    char.exp as 'total_exp',
    char.job as 'job_id',
    char.jlv as 'job_level',
    char.guild_id as 'guild_id',
    char.create_time as 'char_created',
    char.login_time as 'char_logged_in',
    char.logout_time as 'char_logged_out',

    char.job_0 as 'job_id-0',
    char.jlv_0 as 'job_level-0',
    char.job_1 as 'job_id-1',
    char.jlv_1 as 'job_level-1',
    char.job_2 as 'job_id-2',
    char.jlv_2 as 'job_level-2',

    char.belt_00 as 'belt_slot-0',
    char.belt_01 as 'belt_slot-1',
    char.belt_02 as 'belt_slot-2',
    char.belt_03 as 'belt_slot-3',
    char.belt_04 as 'belt_slot-4',
    char.belt_05 as 'belt_slot-5',

    char.summon_0 as 'summon_slot-0',
    char.summon_1 as 'summon_slot-1',
    char.summon_2 as 'summon_slot-2',
    char.summon_3 as 'summon_slot-3',
    char.summon_4 as 'summon_slot-4',
    char.summon_5 as 'summon_slot-5'

FROM ".$this->getTelecasterTable("Character")." as char

WHERE
    char.sid = ?
  AND
	NOT SUBSTRING(char.name, 1, 1) = '@'";

$query_mysql = $query_mssql;

?>