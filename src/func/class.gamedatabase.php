<?php

if(!defined('DirectAccess')) die();

/**
 * Handle connection to MS SQL Database with different types of drivers or functions
 *
 * @author Freakzlike
 * @version 1.0
 */

class GameDatabase {

    /**
     * Static variable to access current class object
     * if using multiple game database this has to be changed
     * @var GameDatabase
     */
    private static $gameDatabase;

    /**
     * Object of GameDBConnection to access game database
     * @var GameDBConnection
     */
    private $gameDBConnection;

    /**
     * Name of database "Auth"
     * @var String
     */
    private $dbNameAuth;

    /**
     * Name of database "Telecaster"
     * @var String
     */
    private $dbNameTelecaster;

    /**
     * Name of database "Arcadia"
     * @var String
     */
    private $dbNameArcadia;

    /**
     * Prefix of tables
     * E.g. Telecaster.dbo.Character -> Prefix = "dbo."
     * @var String
     */
    private $tablePrefix;

    /**
     * Constructor of current class
     * @param  String $pSettingsFile -> Path to settings file
     */
    public function __construct($pSettingsFile) {
        /**
         * Example settings file:
         *  <?php
         *      $db_connection_type = 0;
         *      $db_database_type = 0;
         *      $db_host = "localhost";
         *      $db_user = "username";
         *      $db_password = "password";
         *
         *      $db_dbAuth = "Auth";
         *      $db_dbTelecaster = "Telecaster";
         *      $db_dbArcadia = "Arcaida";
         *      $db_tablePrefix = "dbo.";
         *  ?>
         */
        if (file_exists($pSettingsFile)) {
            require($pSettingsFile);
        } else {
            die(write_errorlog("Cannot find settings file for class GameDatabase. '".$pSettingsFile."' given",2,"class.gamedatabase.php"));
        }

        // get variables from settings file
        if(!isset($db_connection_type) or !isset($db_database_type) or !isset($db_host) or !isset($db_user) or
            !isset($db_password) or !isset($db_dbAuth) or !isset($db_dbTelecaster) or !isset($db_dbArcadia) ) {
            die(write_errorlog("Invalid settings file '".$pSettingsFile."'",2,"class.gamedatabase.php"));
        }

        // build connection to game database
        $this->gameDBConnection = new GameDBConnection($db_connection_type, $db_database_type);
        $this->gameDBConnection->connect($db_host, $db_user, $db_password);

        // set names of databases
        $this->dbNameAuth = $db_dbAuth;
        $this->dbNameTelecaster = $db_dbTelecaster;
        $this->dbNameArcadia = $db_dbArcadia;
        $this->tablePrefix = $db_tablePrefix;
    }

    /**
     * Destructor of current class
     */
    public function __destruct() {
        $this->gameDBConnection->close();
    }

    /**
     * Execute and prepare query if necessary
     * @param  String $queryName -> Name of query / file name in queries directory
     * @param  Array $parameter (optional) -> Array of parameter
     * @param  Array $types (optional) -> Array of types
     * @return Query Result
     */
    public function query($queryName, $parameter=null, $types=null) {
        // Prepared query?
        if(is_null($types) and is_null($parameter)) {
            return $this->gameDBConnection->query($this->getQuery($queryName));
        } else {
            return $this->gameDBConnection->prepared_query($this->getQuery($queryName),$parameter,$types);
        }
    }

    /**
     * Fetch next row from result
     * @see    query()
     * @param  Query Result $result -> Result from query()
     * @return Array
     */
    public function fetch($result) {
        return $this->gameDBConnection->fetch($result);
    }

    /**
     * Query and fetch first row from result
     * @see    query()
     * @see    fetch()
     * @param  String $queryName -> Name of query / file name in queries directory
     * @param  Array $parameter (optional) -> Array of parameter
     * @param  Array $types (optional) -> Array of types
     * @return Array
     */
    public function queryFirst($queryName, $parameter=null, $types=null) {
        return $this->fetch($this->query($queryName, $parameter, $types));
    }

    /**
     * Return query from selected file
     * @param  String $queryName -> Name of query / file name in queries directory
     * @return String -> Query string
     */
    public function getQuery($queryName) {
        $sql_stmt = "";
        // check if query file exists
        if (file_exists("queries/".$queryName.".php")) {
            // include query file
            include("queries/".$queryName.".php");
            // check if required variables have been set
            if(isset($query_mssql) and isset($query_mysql)) {
                // set query depending on database type
                switch($this->gameDBConnection->getDatabaseType()) {
                    // MSSQL
                    case 0:
                        $sql_stmt = $query_mssql;
                    break;
                    // MySQL
                    case 1:
                        $sql_stmt = $query_mysql;
                    break;
                }
            } else {
                write_errorlog("Invalid query file! Query: '".$queryName."'",2,"class.gamedatabase.php");
            }
        } else {
            write_errorlog("Query file not found! Query: '".$queryName."'",2,"class.gamedatabase.php");
        }
        return $sql_stmt;
    }

    /**
     * Returns full qualified name of table in database Arcadia
     * @param  String $tableName -> Table name
     * @return String -> full qualified name of table
     */
    public function getArcadiaTable($tableName) {
        return $this->getTable($this->dbNameArcadia, $tableName);
    }

    /**
     * Returns full qualified name of table in database Auth
     * @param  String $tableName -> Table name
     * @return String -> full qualified name of table
     */
    public function getAuthTable($tableName) {
        return $this->getTable($this->dbNameAuth, $tableName);
    }

    /**
     * Returns full qualified name of table in database Telecaster
     * @param  String $tableName -> Table name
     * @return String -> full qualified name of table
     */
    public function getTelecasterTable($tableName) {
        return $this->getTable($this->dbNameTelecaster, $tableName);
    }

    /**
     * Returns full qualified name of table in given database
     * @param  String $databaseName -> database name
     * @param  String $tableName -> Table name
     * @return String -> full qualified name of table
     */
    private function getTable($databaseName, $tableName) {
        return $databaseName.".".$this->tablePrefix.$tableName;
    }

    /**
     * Create and return current instance of GameDatabase
     * @return GameDatabase -> Current instance of GameDatabase
     */
    public static function getInstance() {
        if (GameDatabase::$gameDatabase == null) {
            GameDatabase::$gameDatabase = new GameDatabase('settings/_game_server.inc.php');
        }
        return GameDatabase::$gameDatabase;
    }

    /**
     * Return current game database connection
     * @return GameDBConnection -> Current instance of GameDBConnection
     */
    public function getGameDBConnection() {
        return $this->gameDBConnection;
    }
}

?>