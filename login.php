<?php
require_once('./init.php');

// если юзер уже залогинен, то просто возвращаем на главную
if (isset($_SESSION['user'])) {
    header('Location: /');
    exit();
}

$found_errors = [];

$trimmed_email = isset($_POST['email']) ? trim($_POST['email'], " \t\n\r") : "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $found_errors = validate_login();

    if (!count($found_errors)) {
        $user = get_user_by_email($link, $trimmed_email);

        $_SESSION['user'] = $user;

        header('Location: /');
        exit();
    }
}

$content = include_template('login.php', [
    'categories' => $full_categories,
    'found_errors' => $found_errors,
    'have_errors' => count($found_errors) > 0,
    'email' => $trimmed_email
]);

$layout = include_template('layout.php', [
    'title' => 'Войти',
    'content' => $content,
    'categories' => $full_categories
]);

print($layout);
