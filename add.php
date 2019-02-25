<?php
require_once('./init.php');

$found_errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $found_errors = validate_lot();

    if (!count($found_errors)) {
        $path = $_FILES['lot-photo']['name'];
        $ext = pathinfo($path, PATHINFO_EXTENSION);

        $filename = uniqid() . '.' . $ext;

        $file_path = 'img/' . $filename;

        move_uploaded_file($_FILES['lot-photo']['tmp_name'], $file_path);

        $lot = [
            'title' => $_POST['lot-name'],
            'description' => $_POST['message'],
            'image_url' => $file_path,
            'start_price' => $_POST['lot-rate'],
            'end_at' => $_POST['lot-date'],
            'bet_step' => $_POST['lot-step'],
            'author_id' => 1,
            'category_id' => $_POST['category']
        ];

        $lot_id = save_lot($link, $lot);

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
