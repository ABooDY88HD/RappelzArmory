<?php

if(!defined('DirectAccess')) die();

/**
 * Convert string from StringResource
 * "<" and ">" are special characters in Rappelz client and the text
 * between these characters will not be displayed. So remove it here
 */
function convertString($string) {

    return preg_replace('#(<).*?(>)#',"",$string);

}

?>