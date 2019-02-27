<?php
require_once('./init.php');

$found_errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $found_errors = validate_user();

    if (!count($found_errors)) {
        move_photo_to_img('avatar');

        $user = [
            'email' => $_POST['email'],
            'name' => $_POST['name'],
            'password' => $_POST['password'],
            'image_url' => $file_path,
            'contacts' => $_POST['contacts']
        ];

        $user_id = save_user($link, $user);

        header('Location: /login');
    }
}

$content = include_template('sign-up.php', [
    'categories' => $full_categories,
    'found_errors' => $found_errors,
    'have_errors' => count($found_errors) > 0
]);

$layout = include_template('layout.php', [
    'title' => 'Добавить лот',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'content' => $content,
    'categories' => $categories
]);

print($layout);
