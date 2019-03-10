<?php
require_once('./init.php');

if (!isset($_GET['category']) || !is_numeric($_GET['category'])) {
    $content = include_template('404.php', [
        'categories' => $full_categories
    ]);
    $title = 'Нет такой страницы';

    $layout = include_template('layout.php', [
        'title' => $title,
        'content' => $content,
        'categories' => $full_categories
    ]);

    print($layout);
    exit();
}

$category_id = (int) $_GET['category'];
$category_title = '';

foreach ($full_categories as $category) {
    if ($category['id'] === $category_id) {
        $category_title = $category['title'];
    }
}

// категории с таким id не нашлось, значит, это 404
// отдадим всё здесь и не будем делать лишний запрос в базу
if ($category_title === '') {
    $content = include_template('404.php', ['categories' => $full_categories]);
    $title = 'Нет такой страницы';

    $layout = include_template('layout.php', [
        'title' => $title,
        'content' => $content,
        'categories' => $full_categories
    ]);

    print($layout);
    exit();
}

// если же такая категория нашлась, то подготовим другие данные
$title = 'Все лоты в категории ' . $category_title;
$lots = get_category_lots($link, $category_id) ?? [];

$content = include_template('all-lots.php', [
    'lots' => $lots,
    'category_title' => $category_title,
    'categories' => $full_categories
]);

$layout = include_template('layout.php', [
    'title' => $title,
    'content' => $content,
    'categories' => $full_categories
]);

print($layout);
