<?php

$query_mssql = "
SELECT
	item.code as 'item_code',
	item.owner_id as 'item_owner',
	item.level as 'item_level',
	item.enhance as 'item_enhance',
	item.socket_0 as 'item_socket-0',
	item.socket_1 as 'item_socket-1',
	item.socket_2 as 'item_socket-2',
	item.socket_3 as 'item_socket-3',
	item.elemental_effect_attack_point as 'item_elemental_effect_patk',
	item.elemental_effect_magic_point as 'item_elemental_effect_matk'

FROM ".$this->getTelecasterTable("Item")." as item

WHERE
    item.sid = ?";

$query_mysql = $query_mssql;

?>