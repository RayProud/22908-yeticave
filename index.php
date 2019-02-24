<?php
require_once('./functions.php');
require_once('./mysql_helper.php');
date_default_timezone_set('Europe/Moscow');
$is_auth = rand(0, 1);

$user_name = 'Роман Прудников';

$link = get_connection();

$full_categories = get_categories($link);

$categories = array_column($full_categories, 'title');

if (isset($_GET['lot'])) {
    $lot_id = (int) $_GET['lot'];
    $lot = get_lot($link, $lot_id);

    $content = $lot === null
        ? include_template('404.php')
        : include_template('lot.php', ['lot' => $lot]);
} else {
    $lots = get_all_lots($link);

    $content = include_template('index.php', [
        'categories' => $categories,
        'lots' => $lots,
        'timer' => get_time_till_midnight()
    ]);
}

$layout = include_template('layout.php', [
    'title' => 'Главная',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'content' => $content,
    'categories' => $categories
]);

print($layout);

