<?php

$query_mssql = "
SELECT
	item.sid as 'item_id',
	item.wear_info as 'item_wear_info'

FROM ".$this->getTelecasterTable("Item")." as item

WHERE
    item.owner_id = ?
  and
    item.summon_id = ?
  and
	item.account_id = 0 and item.auction_id = 0 and item.keeping_id = 0
  and
    item.wear_info > -1

ORDER BY item.wear_info";

$query_mysql = $query_mssql;

?>