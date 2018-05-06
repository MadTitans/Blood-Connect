<?php
/**
 * Created by PhpStorm.
 * User: Xonshiz
 * Date: 05-05-2018
 * Time: 02:44 PM
 */

    define("PRIVATE_PATH", dirname(__FILE__));
    define("PROJECT_PATH", dirname(PRIVATE_PATH));
    define("PUBLIC_PATH", PROJECT_PATH . '/public');

    require_once ('db_credentials.php');
    require_once ('global_functions.php');

    // What this page should return in header fields
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
    header('Content-Type: application/json');

?>