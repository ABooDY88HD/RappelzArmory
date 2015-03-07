<?php

$query_mssql = "
SELECT
    char.job as 'job_id',
	count(char.sid) as 'number_character'

FROM ".$this->getTelecasterTable("Character")." as char

WHERE
	char.name not like '@%'

GROUP BY char.job

ORDER BY 2";

$query_mysql = $query_mssql;

?>