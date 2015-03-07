<?php

if(!defined('DirectAccess')) die();

// start session
session_name('rappelz_armory_session');
session_start();

if(isset($_SESSION['error'])) {
    $now = strtotime('-1 minute');

    // check for next try
    if($now > $_SESSION['error']) {
        unset($_SESSION['error']);
    }
    else {
        echo file_get_contents('template/error_page.html');
        exit();
    }
}

/************************************************************
 *                  FUNCTIONS
 ***********************************************************/

/**
 * for easier handling of class WebDatabase
 */
function webDB() {
    return WebDatabase::getConnection();
}

function webTable($table) {
    return WebDatabase::getTable($table);
}


/**
 * writes error log
 */
function write_errorlog($message,$level,$source,$inFile=0) {
    if($GLOBALS['develop'] == 1) {
        echo "<b>ERROR MESSAGE:</b> ".$message."; <b>LEVEL: ".$level."; SOURCE: ".$source.";</b>";
    }
    else {
        $now = date("Y-m-d H:i:s");
        if($inFile == 0) {
            $result_query = webDB()->query("INSERT INTO ".webTable("logging")." (datetime, source, message, level) VALUES ('".$now."', '".$source."', '".$mysqli->real_escape_string($message)."', '".$level."')");
        }
        else {
            file_put_contents("log/Errorlog_".date("Y-m").".txt",
                    $now.": ".$message."; LEVEL: ".$level."; SOURCE: ".$source."; \n",
                    FILE_APPEND);
        }

        // display error page
        if($level <= 2) {
            $_SESSION['error'] = strtotime('now');
            header('Location: index.php');
        }
    }
}

/**
 * return current url
 *
 * @return String
 */
function getCurrentURL() {

    $url = $_SERVER['PHP_SELF'];
    $gets = http_build_query ($_GET);
    if(strlen($gets) > 0) {
        $url = $url.'?'.$gets;
    }

    return $url;
}

/**
 * return new url with parameter
 *
 * @param  Array Parameterlist
 * @return String
 */
function createNewURL($parameter) {

    $newUrl = getCurrentURL();

    $first = TRUE;

    foreach($parameter as $var => $value) {
        if($first) {
            if(!strpos($newUrl, '?')) {
                $newUrl = $newUrl.'?'.$var."=".$value;
            }
            else {
                $newUrl = $newUrl.'&'.$var."=".$value;
            }
            $first = FALSE;
        }
        else {
            $newUrl = $newUrl.'&'.$var."=".$value;
        }
    }

    return $newUrl;
}

/**
 * Returns text from $language_array at passed Key
 *
 * @param  String $key -> Assoc-Array Key
 * @return String -> Text
 */
function getTranslation($key) {
    global $language_array;

    // check whether array has been set
    if(!isset($language_array)) {
        write_errorlog("Language Array not defined.",3,"sitefunc.inc.php");
        return $key;
    }

    // check if key exists
    if(array_key_exists($key, $language_array)) {
        return $language_array[$key];
    }
    else {
        write_errorlog('Key "'.$key.'" does not exists in TextArray!',3,"sitefunc.inc.php");
        return $key;
    }
}

/**
 * Echos declaration for stylesheets
 */
function echoStylesheets() {
    // check if file exists
    if(file_exists("css/index.php")) {
        // include stylesheet list
        include("css/index.php");

        foreach($stylesheet as $cssfile) {
            echo '<link href="css/'.$cssfile.'.css" rel="stylesheet" type="text/css">'."\n";
        }
    }
}

?>