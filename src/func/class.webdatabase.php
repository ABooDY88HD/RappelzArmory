<?php

if(!defined('DirectAccess')) die();

class WebDatabase {

    private static $webDatabase;

    private $webDBConnection;

    private $tablePrefix;

    private function __construct() {
        /**
         * Example settings file:
         *  <?php
         *      $db_host = "localhost";
         *      $db_user = "username";
         *      $db_password = "password";
         *      $db_database = "database";
         *      $table_prefix = "";
         *  ?>
         */
        $pSettingsFile = "settings/_web_database.inc.php";

        if (file_exists($pSettingsFile)) {
            require($pSettingsFile);
        } else {
            die(write_errorlog("Cannot find settings file for class WebDatabase! File: ".$pSettingsFile.";",1,"Settings Error",1));
        }

        // get variables from settings file
        if(!isset($db_host) or !isset($db_user) or !isset($db_password) or !isset($db_database) or !isset($table_prefix)) {
            die(write_errorlog("Invalid settings file for WebDatabase! File: ".$pSettingsFile.";",1,"Settings Error",1));
        }

        $this->webDBConnection = new WebDBConnection($db_host, $db_user, base64_decode($db_password), $db_database);
    }

    function __destruct() {
        $this->webDBConnection->close();
    }

    public static function getConnection() {
        if (WebDatabase::$webDatabase == null) {
            WebDatabase::$webDatabase = new WebDatabase();
        }
        return WebDatabase::$webDatabase->webDBConnection;
    }

    public static function getTable($table) {
        if ($webDatabase == null) {
            $webDatabase = new WebDatabase();
        }
        return $webDatabase->tablePrefix.$table;
    }

}

?>