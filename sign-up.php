<?php
require_once('./init.php');

// если юзер уже залогинен, то не надо регистрировать, а просто возвращаем на главную
if (isset($_SESSION['user'])) {
    header('Location: /');
    exit();
}

$found_errors = [];

$trimmed_email = isset($_POST['email']) ? trim_if_string($_POST['email']) : "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $found_errors = validate_user();

    if (!count($found_errors)) {
        $file_path = move_photo_to_img('avatar');

        $user = [
            'email' => $trimmed_email,
            'name' => $_POST['name'],
            'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
            'image_url' => $file_path ?? '',
            'contacts' => $_POST['contacts']
        ];

        $user_id = save_user($link, $user);

        header('Location: /login.php');
        exit();
    }
}

$content = include_template('sign-up.php', [
    'categories' => $full_categories,
    'found_errors' => $found_errors,
    'have_errors' => count($found_errors) > 0,
    'email' => $trimmed_email,
    'user_name' => $_POST['name'] ?? '',
    'contacts' => $_POST['contacts'] ?? ''
]);

$layout = include_template('layout.php', [
    'title' => 'Зарегистрироваться',
    'content' => $content,
    'categories' => $full_categories
]);

print($layout);
