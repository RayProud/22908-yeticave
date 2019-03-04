<?php
require_once('./init.php');

if (isset($_GET['lot'])) {
    $lot_id = (int) $_GET['lot'];
    $lot = get_lot($link, $lot_id);

    $content = $lot === null
        ? include_template('404.php')
        : include_template('lot.php', ['lot' => $lot]);

    $title = $lot === null
        ? 'Нет такой страницы'
        : $lot['title'];
} else {
    $content = include_template('404.php');
    $title = 'Нет такой страницы';
}

$layout = include_template('layout.php', [
    'title' => $title,
    'content' => $content,
    'categories' => $categories
]);

print($layout);
