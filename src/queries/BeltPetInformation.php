<?php

$query_mssql = "
SELECT
	itemRes.icon_file_name as 'icon_file_name',
	stringItemRes.value as 'item_name',
    summonRes.rate as 'pet_tier',
	summon.TRANSFORM as 'pet_evo_level',
	summon.LV as 'pet_level',
	CASE
		WHEN summon.TRANSFORM = 1 THEN
			CASE WHEN summon.MAX_LEVEL > 50 THEN summon.MAX_LEVEL - 50 ELSE	0 END
		WHEN summon.TRANSFORM = 2 THEN
			CASE WHEN summon.MAX_LEVEL > 100 THEN (summon.PREV_LEVEL_01 - 50) + (summon.MAX_LEVEL - 100) ELSE summon.PREV_LEVEL_01 - 50 END
		WHEN summon.TRANSFORM = 3 THEN
			(summon.PREV_LEVEL_01 - 50) + (summon.PREV_LEVEL_02 - 100)
	END as 'pet_overbreed'

FROM ".$this->getTelecasterTable("item")." as item

INNER JOIN ".$this->getArcadiaTable("ItemResource")." as itemRes
	ON itemRes.id = item.code

INNER JOIN ".$this->getArcadiaTable("StringResource")." as stringItemRes
	ON stringItemRes.code = itemRes.name_id

INNER JOIN ".$this->getTelecasterTable("Summon")." as summon
	ON summon.CARD_UID = item.sid

LEFT JOIN ".$this->getArcadiaTable("SummonResource")." as summonRes
	ON summonRes.id = summon.CODE

WHERE item.sid = ?";

$query_mysql = $query_mssql;

?>