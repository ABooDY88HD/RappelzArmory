<?php

$query_mssql = "
SELECT TOP 10
	item.sid as 'item_id'

FROM ".$this->getTelecasterTable("Item")." as item

INNER JOIN ".$this->getArcadiaTable("ItemResource")." as itemRes
    ON itemRes.id = item.code
    and itemRes.type = 1
	and itemRes.[group] in (2,3,4,5,6,7,8)
	and itemRes.available_period = 0

INNER JOIN ".$this->getTelecasterTable("Character")." as owner
    ON item.owner_id > 0
	AND owner.sid = item.owner_id
    AND NOT SUBSTRING(owner.name, 1, 1) = '@'

ORDER BY
    item.enhance DESC,
    item.level DESC,
    itemRes.rank DESC,
    CASE
        WHEN item.elemental_effect_attack_point > 0 THEN
            item.elemental_effect_attack_point
        ELSE
            item.elemental_effect_magic_point
    END DESC";

$query_mysql = $query_mssql;

?>