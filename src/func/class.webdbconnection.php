<?php

if(!defined('DirectAccess')) die(); 

/**
 * Handle connection to game database to support different drivers and databases
 *
 * @author Freakzlike
 * @version 1.0
 */

class WebDBConnection extends MySQLi {

    /**
     * Constructor of current class
     * @param string $host MySQL hostname
     * @param string $user MySQL username
     * @param string $pass MySQL password (use null for no password)
     * @param string $db MySQL database to select (use null for none)
     * @param string $port MySQL port to connect to (use null for default)
     * @param string $socket MySQL socket to be used (use null for default)
     */
    public function __construct($host = 'localhost', $user = null, $pass = null,
                                $db = null, $port = null, $socket = null) {
        // set connection type
        @parent::__construct($host, $user, $pass, $db, $port, $socket);
        // check if connect errno is set
        if ($this->connect_errno != 0) {
            die(write_errorlog("MySQL Message: ".$this->connect_errno." - ".$this->connect_error,1,"MySQL Error",1));
        }
    }

    /**
     * Connect to given database server
     * @param  String $pServerName -> name of database server
     * @param  String $pDBUser -> user for database
     * @param  String $pDBPassword -> password for database user
     * @return Boolean -> Flag whether connection was successful
     */
    public function query($sql) {

        if(isset($GLOBALS['sqllog']) and $GLOBALS['sqllog'] == 1) {
            file_put_contents("log/Web_SQL_log_".date("Y-m").".txt",
                    $now = date("Y-m-d H:i:s").": ".$sql."; \n",
                    FILE_APPEND);
        }

        $result = @parent::query($sql);
        // check if errno is set
        if ($this->errno != 0) {
            die(write_errorlog("MySQL Message: ".$this->errno." - ".$this->error."; QUERY: ".$query,2,"MySQL Error",1));
        }
        return $result;
    }
}

?>