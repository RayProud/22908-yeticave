<?php
require_once('./functions.php');
require_once('./mysql_helper.php');
date_default_timezone_set('Europe/Moscow');
$is_auth = rand(0, 1);

$user_name = 'Роман Прудников';

$link = get_connection();

$categories = get_categories($link);

$found_errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $found_errors = validate_lot();
}

$content = include_template('add.php', [
    'categories' => $categories,
    'found_errors' => $found_errors
]);

$layout = include_template('layout.php', [
    'title' => 'Добавить лот',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'content' => $content,
    'categories' => $categories
]);

print($layout);
