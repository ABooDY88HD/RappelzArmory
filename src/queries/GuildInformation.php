<?php

$query_mssql = "
SELECT
    guild.NAME as 'guild_name',
    guild.leader_id as 'guild_leader',
    dungeonString.value as 'dungeon_name',
    (SELECT count(char.sid)
	    FROM ".$this->getTelecasterTable("Character")." as char
	    WHERE NOT SUBSTRING(char.name, 1, 1) = '@') as 'number_member',
    (SELECT avg(char.lv)
	    FROM ".$this->getTelecasterTable("Character")." as char
	    WHERE NOT SUBSTRING(char.name, 1, 1) = '@') as 'avg_member_level'

FROM ".$this->getTelecasterTable("Guild")." as guild

LEFT JOIN ".$this->getArcadiaTable("WorldLocation")." as dungeon
    ON guild.DUNGEON_ID > 0
    AND dungeon.id = guild.DUNGEON_ID

LEFT JOIN ".$this->getArcadiaTable("StringResource")." as dungeonString
    ON dungeon.text_id > 0
    AND dungeonString.code = dungeon.text_id

WHERE
    guild.SID = ?";

$query_mysql = $query_mssql;

?>