<?php
require_once('./functions.php');
require_once('./mysql_helper.php');
date_default_timezone_set('Europe/Moscow');
$is_auth = rand(0, 1);

$user_name = 'Роман Прудников';

$link = mysqli_init();

if (!$link) {
    die('Error link');
}

if (!mysqli_options($link, MYSQLI_OPT_INT_AND_FLOAT_NATIVE, 1)) {
    die('Error mysqli_options');
}

if (!mysqli_real_connect($link, '127.0.0.1', 'root', '', 'yeticave')) {
    die('A db connection error occured: ' . mysqli_connect_error());
}

mysqli_set_charset($link, "utf8");

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

