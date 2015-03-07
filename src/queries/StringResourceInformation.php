<?php

$query_mssql = "
SELECT
	stringRes.value as 'string_value'

FROM ".$this->getArcadiaTable("StringResource")." as stringRes

WHERE
    stringRes.code = ?";

$query_mysql = $query_mssql;

?>