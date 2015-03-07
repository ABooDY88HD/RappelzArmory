<?php

$query_mssql = "
SELECT

(SELECT count(char.sid)
	FROM ".$this->getTelecasterTable("Character")."  as char
	WHERE NOT SUBSTRING(char.name, 1, 1) = '@'
		AND char.race = 3)
    'total_gaia_character',

(SELECT count(char.sid)
	FROM ".$this->getTelecasterTable("Character")."  as char
	WHERE NOT SUBSTRING(char.name, 1, 1) = '@'
		AND char.race = 4)
    'total_deva_character',

(SELECT count(char.sid)
	FROM ".$this->getTelecasterTable("Character")."  as char
	WHERE NOT SUBSTRING(char.name, 1, 1) = '@'
		AND char.race = 5)
    'total_asura_character',

(SELECT count(char.sid)
	FROM ".$this->getTelecasterTable("Character")."  as char
	WHERE NOT SUBSTRING(char.name, 1, 1) = '@'
		AND char.lv < 20)
    'total_r1_character',

(SELECT count(char.sid)
	FROM ".$this->getTelecasterTable("Character")."  as char
	WHERE NOT SUBSTRING(char.name, 1, 1) = '@'
		AND (char.lv >= 20 AND char.lv < 50))
    'total_r2_character',

(SELECT count(char.sid)
	FROM ".$this->getTelecasterTable("Character")."  as char
	WHERE NOT SUBSTRING(char.name, 1, 1) = '@'
		AND (char.lv >= 50 AND char.lv < 80))
    'total_r3_character',

(SELECT count(char.sid)
	FROM ".$this->getTelecasterTable("Character")."  as char
	WHERE NOT SUBSTRING(char.name, 1, 1) = '@'
		AND (char.lv >= 80 AND char.lv < 100))
    'total_r4_character',

(SELECT count(char.sid)
	FROM ".$this->getTelecasterTable("Character")."  as char
	WHERE NOT SUBSTRING(char.name, 1, 1) = '@'
		AND (char.lv >= 100 AND char.lv < 120))
    'total_r5_character',

(SELECT count(char.sid)
	FROM ".$this->getTelecasterTable("Character")."  as char
	WHERE NOT SUBSTRING(char.name, 1, 1) = '@'
		AND (char.lv >= 120 AND char.lv < 150))
    'total_r6_character',

(SELECT count(char.sid)
	FROM ".$this->getTelecasterTable("Character")."  as char
	WHERE NOT SUBSTRING(char.name, 1, 1) = '@'
		AND char.lv >= 150)
    'total_r7_character'
";

$query_mysql = $query_mssql;

?>