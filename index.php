<?php
require_once('./functions.php');
$is_auth = rand(0, 1);

$user_name = 'Роман Прудников';
$categories = ['Доски и лыжи', 'Крепления', 'Ботинки', 'Одежда', 'Инструменты', 'Разное'];
$lots = [
    ['title' => '2014 Rossignol District Snowboard', 'category' => 'Доски и лыжи', 'price' => 10999.2, 'image' => 'img/lot-1.jpg'],
    ['title' => 'DC Ply Mens 2016/2017 Snowboard', 'category' => 'Доски и лыжи', 'price' => 159999.99, 'image' => 'img/lot-2.jpg'],
    ['title' => 'Крепления Union Contact Pro 2015 года размер L/XL', 'category' => 'Крепления', 'price' => 8000, 'image' => 'img/lot-3.jpg'],
    ['title' => 'Ботинки для сноуборда DC Mutiny Charocal', 'category' => 'Ботинки', 'price' => 10999.0, 'image' => 'img/lot-4.jpg'],
    ['title' => 'Куртка для сноуборда DC Mutiny Charocal', 'category' => 'Одежда', 'price' => 7500.0, 'image' => 'img/lot-5.jpg'],
    ['title' => 'Маска Oakley Canopy', 'category' => 'Разное', 'price' => 5400.0, 'image' => 'img/lot-6.jpg']
];

$content = include_template('index.php', [
    'categories' => $categories,
    'lots' => $lots
]);

$layout = include_template('layout.php', [
    'title' => 'Главная',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'content' => $content,
    'categories' => $categories
]);

print($layout);

