<?php
require_once('./init.php');

$found_errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $found_errors = validate_lot();

    if (!count($found_errors)) {
        move_photo_to_img('lot-photo');

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

$have_errors = count($found_errors) > 0;

$content = include_template('add.php', [
    'categories' => $full_categories,
    'found_errors' => $found_errors,
    'have_errors' => $have_errors,
    'lot_name' => $_POST['lot-name'] ?? '',
    'message' => $_POST['message'] ?? '',
    'lot_rate' => $_POST['lot-rate'] ?? '',
    'lot_date' => $_POST['lot-date'] ?? '',
    'lot_step' => $_POST['lot-step'] ?? '',
    'lot_category' => $_POST['category'] ?? ''
]);

$layout = include_template('layout.php', [
    'title' => 'Добавить лот',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'content' => $content,
    'categories' => $categories
]);

print($layout);
