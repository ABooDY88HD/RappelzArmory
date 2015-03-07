<?php

$query_mssql = "
SELECT
	char.sid as 'char_id'

FROM ".$this->getTelecasterTable("Character")." as char

WHERE
	char.name not like '@%'
    AND
    char.guild_id = ?

ORDER BY char.exp DESC";

$query_mysql = $query_mssql;

?>