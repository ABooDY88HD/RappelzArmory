<?php

$query_mssql = "
SELECT
    stringJobRes.value as 'job_name',
    jobRes.icon_file_name as 'job_icon_file',
    jobRes.job_depth as 'job_depth',
    jobRes.job_class as 'job_class'

FROM ".$this->getArcadiaTable("JobResource")." as jobRes

LEFT JOIN ".$this->getArcadiaTable("StringResource")." as stringJobRes
    ON jobRes.text_id = stringJobRes.code

WHERE
    jobRes.id = ?";

$query_mysql = $query_mssql;

?>