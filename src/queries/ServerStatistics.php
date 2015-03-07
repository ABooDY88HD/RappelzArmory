<?php

$query_mssql = "
SELECT

(SELECT count(account.account_id)
	FROM ".$this->getAuthTable("Accounts")." as account) as
    'total_accounts',

(SELECT count(char.sid)
	FROM ".$this->getTelecasterTable("Character")." as char
	WHERE NOT SUBSTRING(char.name, 1, 1) = '@') as
    'total_character',

(SELECT count(char.sid)
	FROM ".$this->getTelecasterTable("Character")." as char
	WHERE NOT SUBSTRING(char.name, 1, 1) = '@'
		AND char.login_time > char.logout_time) as
    'online_player',

(SELECT count(char.sid)
	FROM ".$this->getTelecasterTable("Character")." as char
	WHERE NOT SUBSTRING(char.name, 1, 1) = '@'
		AND DATEDIFF(day, char.logout_time, GETDATE()) <= 20) as
    'active_character',

(SELECT  count(guild.sid)
	FROM ".$this->getTelecasterTable("Guild")." as guild) as
    'total_guilds',

(SELECT count(alliance.sid)
	FROM ".$this->getTelecasterTable("Alliance")." as alliance) as
    'total_alliance',

(SELECT count(party.sid)
	FROM ".$this->getTelecasterTable("Party")." as party) as
    'current_parties'";

$query_mysql = $query_mssql;

?>