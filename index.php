<?php
require_once('./functions.php');
require_once('./mysql_helper.php');
date_default_timezone_set('Europe/Moscow');
$is_auth = rand(0, 1);

$user_name = 'Роман Прудников';

$link = get_connection();

$lots = get_lots($link);
$categories = get_categories($link);

$content = include_template('index.php', [
    'categories' => $categories,
    'lots' => $lots,
    'timer' => get_time_till_midnight()
]);

$layout = include_template('layout.php', [
    'title' => 'Главная',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'content' => $content,
    'categories' => $categories
]);

print($layout);

