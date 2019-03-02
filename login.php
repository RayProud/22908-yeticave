<?php
require_once('./init.php');

$found_errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $found_errors = validate_login();

    if (!count($found_errors)) {
        // аутентификация

        header('Location: /');
    }
}

$content = include_template('login.php', [
    'categories' => $full_categories,
    'found_errors' => $found_errors,
    'have_errors' => count($found_errors) > 0,
    'email' => $_POST['email'] ?? ''
]);

$layout = include_template('layout.php', [
    'title' => 'Войти',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'content' => $content,
    'categories' => $categories
]);

print($layout);
