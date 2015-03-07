<?php
    /**
     * Information:
     * Only allow direct access to this index file
     */

    $GLOBALS['develop'] = 1; // 0 = public mode, 1 = develop mode
    $GLOBALS['sqllog'] = 0; // 1 = log sqls to text file

    // Develop options
    if($GLOBALS['develop'] == 1) {
        error_reporting(E_ALL);
    }
    else {
        error_reporting(0);
    }

    // constant to avoid direct access to an include file
    // Also only allowed access to this index file in your .htaccess
    define("DirectAccess", TRUE);

    // include function library
    require_once('func/library.inc.php');

    // include fix language
    require_once('language/en.php');

?>
<!DOCTYPE HTML>

<html>

<head>
    <?php echoStylesheets(); ?>
    <title>Pagetitle</title>
</head>

<body id="main">

    <div id="wrapper">

        <div id="header">
            <img src="images/logo.png" alt="Rappelz Armory" title="Rappelz Armory" align="center"/>
        </div>

        <div id="navigation">
            <a href="index.php"><?php echo getTranslation('top_character'); ?></a> -
            <a href="index.php?site=best_items"><?php echo getTranslation('best_items'); ?></a> -
            <a href="index.php?site=server_statistics"><?php echo getTranslation('server_statistics'); ?></a>
        </div>

        <div id="content">
            <?php
                if(isset($_GET['site']) and !preg_match('/[^0-9-_a-z]/i', $_GET['site']) and file_exists("site/".$_GET['site'].".php")) {
                    include("site/".$_GET['site'].".php");
                } else {
                    include("site/top_characters.php");
                }
            ?>
        </div>

        <div id="footer">
            Rappelz Armory - Open Source Project
        </div>

    </div>

</body>

</html>