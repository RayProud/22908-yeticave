<?php
require_once('./functions.php');
require_once('./mysql_helper.php');
session_start();
date_default_timezone_set('Europe/Moscow');

$link = get_connection();

$full_categories = get_categories($link);
$categories = array_column($full_categories, 'title');
