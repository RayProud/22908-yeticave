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

$get_newest_lots_query = 'SELECT l.title, l.start_price, l.image_url, c.title AS category_title
FROM lot l
       JOIN category c
            ON l.category_id=c.id
WHERE l.end_at >= NOW()
ORDER BY l.created_at DESC;';
$response = mysqli_query($link, $get_newest_lots_query);

if ($response === false) {
    die('A query error occured: ' . mysqli_error($link));
}

$lots = mysqli_fetch_all($response, MYSQLI_ASSOC);

$get_categories_query = 'SELECT title FROM category';

$response = mysqli_query($link, $get_categories_query);

if ($response === false) {
    die('A query error occured: ' . mysqli_error($link));
}

$categories = array_column(mysqli_fetch_all($response, MYSQLI_ASSOC), 'title');

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

