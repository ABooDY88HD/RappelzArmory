<?php

$query_mssql = "
SELECT
	jobRes.id as 'job_id',
	stringJobRes.value as 'job_name',
    jobRes.icon_file_name as 'job_icon_file'

FROM ".$this->getArcadiaTable("JobResource")." as jobRes

LEFT JOIN ".$this->getArcadiaTable("StringResource")." as stringJobRes
    ON jobRes.text_id = stringJobRes.code";

$query_mysql = $query_mssql;

?>