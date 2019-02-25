<?php
require_once('./functions.php');
require_once('./mysql_helper.php');
date_default_timezone_set('Europe/Moscow');
$is_auth = rand(0, 1);

$user_name = 'Роман Прудников';

$link = get_connection();

$full_categories = get_categories($link);
$categories = array_column($full_categories, 'title');
