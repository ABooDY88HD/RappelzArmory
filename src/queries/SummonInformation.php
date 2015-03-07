<?php

$query_mssql = "
SELECT
    summon.sid as 'summon_id',
    summon.card_uid as 'summon_itemid',
    summon.OWNER_ID as 'summon_owner',
	summon.NAME as 'summon_name',
	stringSummonRes.value as 'summon_type_name',
    summonRes.rate as 'summon_tier',
	summon.TRANSFORM as 'summon_evo_level',
	summon.LV as 'summon_level',
	CASE
		WHEN summon.TRANSFORM = 1 THEN
			CASE WHEN summon.MAX_LEVEL > 50 THEN summon.MAX_LEVEL - 50 ELSE	0 END
		WHEN summon.TRANSFORM = 2 THEN
			CASE WHEN summon.MAX_LEVEL > 100 THEN (summon.PREV_LEVEL_01 - 50) + (summon.MAX_LEVEL - 100) ELSE summon.PREV_LEVEL_01 - 50 END
		WHEN summon.TRANSFORM = 3 THEN
			(summon.PREV_LEVEL_01 - 50) + (summon.PREV_LEVEL_02 - 100)
	END as 'summon_overbreed',
    summonRes.face_file_name as 'summon_icon_file',
    summonRes.illust_file_name as 'summon_illust_file'

FROM ".$this->getTelecasterTable("Summon")." as summon

INNER JOIN ".$this->getArcadiaTable("SummonResource")." as summonRes
	ON summonRes.id = summon.CODE

LEFT JOIN ".$this->getArcadiaTable("StringResource")." as stringSummonRes
    ON stringSummonRes.code = summonRes.name_id

WHERE
    summonRes.form <> 0 AND summonRes.is_riding_only <> 1
    AND
    ((? = -1) or (summon.sid = ?))
    AND
    ((? = -1) or (summon.card_uid = ?))";

$query_mysql = $query_mssql;

?>