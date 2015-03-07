<?php

$query_mssql = "
SELECT TOP 100
	char.sid as 'char_id'

FROM ".$this->getTelecasterTable("Character")." as char

WHERE
	char.name not like '@%'
  and
    (? = 0 or char.job = ?)

ORDER BY char.exp DESC";

$query_mysql = $query_mssql;

?>