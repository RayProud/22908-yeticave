<?php
require_once('./init.php');

$found_errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $found_errors = validate_login();

    if (!count($found_errors)) {
        $user = get_user_by_email($link, $_POST['email']);

        $_SESSION['user'] = $user;

        header('Location: /');
        exit();
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
    'content' => $content,
    'categories' => $categories
]);

print($layout);
