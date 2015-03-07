<?php

$query_mssql = "
SELECT
	itemResString.value as 'item_name',
	itemRes.tooltip_id as 'item_tooltip_id',
    itemRes.icon_file_name as 'item_icon_file',
    itemRes.rank as 'item_rank',
    itemRes.socket as 'item_num_socket',
    itemRes.type as 'item_type',
    itemRes.[group] as 'item_group',
    itemRes.wear_type as 'item_wear_type',
    itemRes.summon_id as 'item_summon_id'

FROM ".$this->getArcadiaTable("ItemResource")." as itemRes

LEFT JOIN ".$this->getArcadiaTable("StringResource")." as itemResString
	ON itemRes.name_id = itemResString.code

WHERE
    itemRes.id = ?";

$query_mysql = $query_mssql;

?>