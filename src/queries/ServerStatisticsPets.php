<?php

$query_mssql = "
SELECT

(SELECT count(summon.sid)
	FROM ".$this->getTelecasterTable("Summon")." as summon
	LEFT JOIN ".$this->getTelecasterTable("Character")." as char
    ON char.sid = summon.owner_id
		AND NOT SUBSTRING(char.name, 1, 1) = '@') as
    'total_tamed_pets',

(SELECT count(summon.sid)
	FROM ".$this->getTelecasterTable("Summon")." as summon
	LEFT JOIN ".$this->getTelecasterTable("Character")." as char
    ON char.sid = summon.owner_id
		AND NOT SUBSTRING(char.name, 1, 1) = '@'
    WHERE summon.TRANSFORM = 1) as
    'total_pets_evo1',

(SELECT count(summon.sid)
	FROM ".$this->getTelecasterTable("Summon")." as summon
	LEFT JOIN ".$this->getTelecasterTable("Character")." as char
    ON char.sid = summon.owner_id
		AND NOT SUBSTRING(char.name, 1, 1) = '@'
    WHERE summon.TRANSFORM = 2) as
    'total_pets_evo2',

(SELECT count(summon.sid)
	FROM ".$this->getTelecasterTable("Summon")." as summon
	LEFT JOIN ".$this->getTelecasterTable("Character")." as char
    ON char.sid = summon.owner_id
		AND NOT SUBSTRING(char.name, 1, 1) = '@'
    WHERE summon.TRANSFORM = 3) as
    'total_pets_evo3',

(SELECT count(summon.sid)
	FROM ".$this->getTelecasterTable("Summon")." as summon
	LEFT JOIN ".$this->getTelecasterTable("Character")." as char
    ON char.sid = summon.owner_id
		AND NOT SUBSTRING(char.name, 1, 1) = '@'
    INNER JOIN ".$this->getArcadiaTable("SummonResource")." as summonRes
    ON summon.CODE = summonRes.id
        AND summonRes.rate = 5) as
    'total_pets_tier6',

(SELECT count(summon.sid)
	FROM ".$this->getTelecasterTable("Summon")." as summon
	LEFT JOIN ".$this->getTelecasterTable("Character")." as char
    ON char.sid = summon.owner_id
		AND NOT SUBSTRING(char.name, 1, 1) = '@'
    INNER JOIN ".$this->getArcadiaTable("SummonResource")." as summonRes
    ON summon.CODE = summonRes.id
        AND summonRes.rate = 4) as
    'total_pets_tier5',

(SELECT count(summon.sid)
	FROM ".$this->getTelecasterTable("Summon")." as summon
	LEFT JOIN ".$this->getTelecasterTable("Character")." as char
    ON char.sid = summon.owner_id
		AND NOT SUBSTRING(char.name, 1, 1) = '@'
    INNER JOIN ".$this->getArcadiaTable("SummonResource")." as summonRes
    ON summon.CODE = summonRes.id
        AND summonRes.rate = 3) as
    'total_pets_tier4',

(SELECT count(summon.sid)
	FROM ".$this->getTelecasterTable("Summon")." as summon
	LEFT JOIN ".$this->getTelecasterTable("Character")." as char
    ON char.sid = summon.owner_id
		AND NOT SUBSTRING(char.name, 1, 1) = '@'
    INNER JOIN ".$this->getArcadiaTable("SummonResource")." as summonRes
    ON summon.CODE = summonRes.id
        AND summonRes.rate = 2) as
    'total_pets_tier3',

(SELECT count(summon.sid)
	FROM ".$this->getTelecasterTable("Summon")." as summon
	LEFT JOIN ".$this->getTelecasterTable("Character")." as char
    ON char.sid = summon.owner_id
		AND NOT SUBSTRING(char.name, 1, 1) = '@'
    INNER JOIN ".$this->getArcadiaTable("SummonResource")." as summonRes
    ON summon.CODE = summonRes.id
        AND summonRes.rate = 1) as
    'total_pets_tier2',

(SELECT count(summon.sid)
	FROM ".$this->getTelecasterTable("Summon")." as summon
	LEFT JOIN ".$this->getTelecasterTable("Character")." as char
    ON char.sid = summon.owner_id
		AND NOT SUBSTRING(char.name, 1, 1) = '@'
    INNER JOIN ".$this->getArcadiaTable("SummonResource")." as summonRes
    ON summon.CODE = summonRes.id
        AND summonRes.rate = 0) as
    'total_pets_tier1'";

$query_mysql = $query_mssql;

?>