<?php

    /**
     * current connection type
     * 0 = ODBC
     *     Information: http://php.net/manual/en/book.uodbc.php
     * 1 = SQLSRV - Microsoft SQL Server Driver for PHP
     *     Information: http://php.net/manual/en/book.sqlsrv.php
     * 2 = MySQLi - MySQL
     *     Able to use copy of game database at MySQL web database for security reasons
     */
    $db_connection_type = 0;
    /**
     * current database type, because of different sql syntax
     * 0 = MSSQL
     * 1 = MySQL
     */
    $db_database_type = 0;

    $db_host = "localhost";
    $db_user = "sa";
    $db_password = "";

    $db_dbAuth = "Auth";
    $db_dbTelecaster = "Telecaster";
    $db_dbArcadia = "Arcadia";
    $db_tablePrefix = "dbo.";

?>