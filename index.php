<?php
require_once('./init.php');

$lots = get_all_lots($link) ?? [];

$content = include_template('index.php', [
    'categories' => $categories,
    'lots' => $lots
]);

$layout = include_template('layout.php', [
    'title' => 'Главная',
    'content' => $content,
    'categories' => $full_categories
]);

print($layout);
