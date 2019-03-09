<?php
require_once('./init.php');

if (!isset($_GET['category']) || !is_numeric($_GET['category'])) {
    $content = include_template('404.php');
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
$category_title = "";

foreach ($full_categories as $category) {
    if ($category['id'] === $category_id) {
        $category_title = $category['title'];
    }
}

$lots = get_category_lots($link, $category_id) ?? [];

$content = include_template('all-lots.php', [
    'lots' => $lots,
    'category_title' => $category_title
]);

$layout = include_template('layout.php', [
    'title' => 'Все лоты в категории ' . $category_title,
    'content' => $content,
    'categories' => $full_categories
]);

print($layout);
