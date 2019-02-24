<?php
require_once('./functions.php');
require_once('./mysql_helper.php');
date_default_timezone_set('Europe/Moscow');
$is_auth = rand(0, 1);

$user_name = 'Роман Прудников';

$link = get_connection();

$full_categories = get_categories($link);

$categories = array_column($full_categories, 'title');

$found_errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $found_errors = validate_lot();

    if (!count($found_errors)) {
        $path = $_FILES['lot-photo']['name'];
        $ext = pathinfo($path, PATHINFO_EXTENSION);

        $filename = uniqid() . '.' . $ext;

        $file_path = 'img/' . $filename;

        move_uploaded_file($_FILES['lot-photo']['tmp_name'], $file_path);

        $lot_id = save_lot($link, $_POST['lot-name'], $_POST['message'], $file_path, $_POST['lot-rate'], $_POST['lot-date'], $_POST['lot-step'], 1, $_POST['category']);

        header('Location: /?lot=' . $lot_id);
    }
}

$content = include_template('add.php', [
    'categories' => $full_categories,
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
