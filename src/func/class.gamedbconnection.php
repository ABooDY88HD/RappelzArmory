<?php

if(!defined('DirectAccess')) die();

/**
 * Handle connection to game database to support different drivers and databases
 *
 * @author Freakzlike
 * @version 1.0
 */

class GameDBConnection {

    /**
     * current connection type
     * 0 = ODBC
     *     Information: http://php.net/manual/en/book.uodbc.php
     * 1 = SQLSRV - Microsoft SQL Server Driver for PHP
     *     Information: http://php.net/manual/en/book.sqlsrv.php
     * 2 = MySQLi - MySQL
     *     Able to use copy of game database at MySQL web database for security reasons
     * @var Integer
     */
    private $connectionType = 0;

    /**
     * current database type
     * required because of different sql syntax
     * 0 = MSSQL
     * 1 = MySQL
     * @var Integer
     */
    private $databaseType = 0;

    /**
     * current connection to database server
     * @var Connection
     */
    private $dbConnection;

    /**
     * Constructor of current class
     * @param  Integer $pConnectionType -> current connection type
     */
    public function __construct($pConnectionType, $pDatabaseType) {

        switch($pConnectionType) {
        case 0: break;
        case 1: break;
        case 2: break;
        default:
            die(write_errorlog("Invalid connection type. '".$pConnectionType."'",2,"class.gamedbconnection.php"));
        break;
        }

        switch($pDatabaseType) {
        case 0: break;
        case 1: break;
        default:
            die(write_errorlog("Invalid database type. '".$pDatabaseType."'",2,"class.gamedbconnection.php"));
        break;
        }

        // attributes
        $this->connectionType = $pConnectionType;
        $this->databaseType = $pDatabaseType;
        $this->dbConnection = FALSE;
    }

    /**
     * Connect to given database server
     * @param  String $pServerName -> name of database server
     * @param  String $pDBUser -> user for database
     * @param  String $pDBPassword -> password for database user
     */
    public function connect($pServerName, $pDBUser, $pDBPassword) {
        switch ($this->connectionType) {
        // ODBC
        case 0:
            $this->dbConnection = odbc_connect($pServerName, $pDBUser, $pDBPassword);
        break;
        // SQLSRV
        case 1:
            $this->dbConnection = sqlsrv_connect($pServerName, array("UID" => $pDBUser, "PWD" => $pDBPassword));
        break;
        // MySQLi
        case 2:
            $this->dbConnection = @new mysqli($pServerName, $pDBUser, $pDBPassword);
            if (mysqli_connect_errno() != 0) {
                $this->dbConnection = FALSE;
            }
        break;
        }

        if($this->dbConnection == FALSE) {
            die(write_errorlog("Failed to connect to Game Database '".$pServerName."'. Error: ".$this->error(1),2,"class.gamedbconnection.php"));
        }
    }

    /**
     * Close connection to database server
     */
    public function close() {
        switch ($this->connectionType) {
        // ODBC
        case 0:
            odbc_close($this->dbConnection);
        break;
        // SQLSRV
        case 1:
            sqlsrv_close($this->dbConnection);
        break;
        // MySQL
        case 2:
            $this->dbConnection->close();
        break;
        }

        $this->dbConnection = FALSE;
    }

    /**
     * Return last error message
     * @param  Integer -> Flag whether error has been occured at connection establishment
     */
    public function error($connect = 0) {
        switch ($this->connectionType) {
        // ODBC
        case 0:
            if(odbc_error($this->dbConnection)) {
                return odbc_errormsg($this->dbConnection);
            }
        break;
        // SQLSRV
        case 1:
            if( ($errors = sqlsrv_errors() ) != null) {
                $errormsg = '';
                foreach( $errors as $error ) {
                    $errormsg = $errormsg."- SQLSTATE: ".$error['SQLSTATE']."; code: ".$error['code']."; message: ".$error['message'];
                }
                return $errormsg;
            }
        break;
        // MySQLi
        case 2:
            if($connect == 0) {
                if ($this->dbConnection->errno != 0) {
                    return $this->dbConnection->errno." - ".$this->dbConnection->error;
                }
            } else {
                if (mysqli_connect_errno() != 0) {
                    // use procedure methods because object is no longer available
                    return mysqli_connect_errno()." - ".mysqli_connect_error();
                }
            }
        break;
        }
        return null;
    }

    /**
     * Query current database connection
     * @param  String $pSqlQuery -> SQL Query which should be performed
     * @return Statement or Boolean
     */
    public function query($pSqlQuery) {

        $this->logQuery($pSqlQuery);

        switch ($this->connectionType) {
        // ODBC
        case 0:
            $stmt = odbc_exec($this->dbConnection, $pSqlQuery);
            $this->checkError($pSqlQuery);
            return $stmt;
        break;
        // SQLSRV
        case 1:
            $stmt = sqlsrv_query($this->dbConnection, $pSqlQuery);
            $this->checkError($pSqlQuery);
            return $stmt;
        break;
        // MySQLi
        case 2:
            $stmt = $this->dbConnection->query($pSqlQuery);
            $this->checkError($pSqlQuery);
            return $stmt;
        break;
        }
    }

    /**
     * Prepare and execute query
     * @param  String $pSqlQuery -> SQL Query which should be performed
     * @param  Array $parameter -> Parameter for SQL query
     * @param  Array $types -> Types of parameters in $parameter
     * @return Statement or Boolean
     */
    public function prepared_query($pSqlQuery, $parameter, $types) {

        // log query
        $this->logQuery($pSqlQuery);

        switch ($this->connectionType) {
        // ODBC
        case 0:
            $stmt = odbc_prepare($this->dbConnection, $pSqlQuery);
            $this->checkError($pSqlQuery);
            $res = odbc_execute($stmt, $parameter);
            $this->checkError($pSqlQuery);
            return $stmt;
        break;
        // SQLSRV
        case 1:
            // this is required to avoid warning because of "call by value"
            $newParameter = array();
            $max = sizeof($parameter);
            for($i=0;$i<=$max;$i++) {
                $newParameter[$i] = &$parameter[$i];
            }
            $stmt = sqlsrv_prepare($this->dbConnection, $pSqlQuery, $newParameter);
            $this->checkError($pSqlQuery);
            sqlsrv_execute($stmt);
            $this->checkError($pSqlQuery);
            return $stmt;
        break;
        // MySQLi
        case 2:
            $stmt = $this->dbConnection->prepare($pSqlQuery);
            $this->checkError($pSqlQuery);
            // call method $stmt->bind_param() dynamically
            if($types&&$parameter)
            {
                $bind_names[] = implode('',$types);
                for ($i=0; $i<count($parameter);$i++)
                {
                    $bind_name = 'bind' . $i;
                    $$bind_name = $parameter[$i];
                    $bind_names[] = &$$bind_name;
                }
                $return = call_user_func_array(array($stmt,'bind_param'),$bind_names);
                $this->checkError($pSqlQuery);
            }
            $stmt->execute();
            $this->checkError($pSqlQuery);
            $res = $stmt->get_result();
            $this->checkError($pSqlQuery);
            return $res;
        break;
        }
    }

    /**
     * Fetch array from statement which has been created with method {@link query()}
     * @see    query()
     * @param  Statement $pStatement -> SQL Statement from method {@link query()}
     * @return Array -> Result of fetch array
     */
    public function fetch($pStatement) {
        switch ($this->connectionType) {
        // ODBC
        case 0:
            $row = odbc_fetch_array($pStatement);
            $this->checkError();
            return $row;
        break;
        // SQLSRV
        case 1:
            $row = sqlsrv_fetch_array($pStatement);
            $this->checkError();
            return $row;
        break;
        // MySQL
        case 2:
            $row = $pStatement->fetch_assoc();
            $this->checkError();
            return $row;
        break;
        }
    }

    /**
     * Return number of rows from statement
     * @see    query()
     * @param  Statement $pStatement -> SQL Statement from method {@link query()}
     * @return Integer -> Number of rows from query or -1 if error
     */
    public function num_rows($pStatement) {
        switch ($this->connectionType) {
        // ODBC
        case 0:
            $num_rows = odbc_num_rows($this->dbConnection, $pStatement);
            $this->checkError();
            return $num_rows;
        break;
        // SQLSRV
        case 1:
            $num_rows = sqlsrv_num_rows($this->dbConnection, $pStatement);
            $this->checkError();
            return $num_rows;
        break;
        // MySQL
        case 2:
            $num_rows = $pStatement->num_rows;
            $this->checkError();
            return $num_rows;
        break;
        }
    }

    /**
     * Check whether error occured and output error message
     * @param  String (optional) -> Last SQL query
     */
    private function checkError($pSqlQuery = null) {
        if(($error = $this->error()) != null) {
            if($pSqlQuery != null) {
                die(write_errorlog("Game DB Message: ".$error."; QUERY: ".$pSqlQuery,2,"Game DB Error"));
            } else {
                die(write_errorlog("Game DB Message: ".$error,2,"Game DB Error"));
            }
        }
    }

    /**
     * Log query in file
     * @param  String -> SQL query
     */
    private function logQuery($pSqlQuery) {
        if(isset($GLOBALS['sqllog']) and $GLOBALS['sqllog'] == 1) {
            file_put_contents("log/Game_SQL_log_".date("Y-m").".txt",
                    $now = date("Y-m-d H:i:s").": ".$pSqlQuery."; \n",
                    FILE_APPEND);
        }
    }

    /**
     * @see    $databaseType
     * @return Integer -> Database type
     */
    public function getDatabaseType() {
        return $this->databaseType;
    }
}

?>