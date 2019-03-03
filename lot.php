<?php
require_once('./init.php');

if (!isset($_GET['lot'])) {
    $content = include_template('404.php');
    $title = 'Нет такой страницы';

    $layout = include_template('layout.php', [
        'title' => $title,
        'content' => $content,
        'categories' => $categories
    ]);

    print($layout);
    exit();
}

$lot_id = (int) $_GET['lot'];
$lot = get_lot($link, $lot_id);
$lot_page_data = [
    'lot' => $lot,
    'price' => $lot['start_price']
];

$found_errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user'])) {
    $found_errors = validate_bet($lot);

    if (!count($found_errors)) {
        $bet = [
            'amount' => $_POST['cost'],
            'author_id' => $_SESSION['user']['id'],
            'lot_id' => $lot_id
        ];

        save_bet($link, $bet);
    }
}

$bets = [];

if (isset($lot_id)) {
    $bets = get_bets($link, $lot_id);
    $max_bet = $bets ? max(array_column($bets, 'amount')) : null;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user']) && !count($found_errors) && !empty($max_bet)) {
        $update_lot = [
            'start_price' => $max_bet,
            'id' => $lot_id
        ];

        update_price($link, $update_lot);

        $lot_page_data['price'] = $max_bet;
    }
}

$lot_page_data['bets'] = $bets ?? [];
$lot_page_data['found_errors'] = $found_errors ?? [];

$content = $lot === null
    ? include_template('404.php')
    : include_template('lot.php', $lot_page_data);

$title = $lot === null
    ? 'Нет такой страницы'
    : $lot['title'];

$layout = include_template('layout.php', [
    'title' => $title,
    'content' => $content,
    'categories' => $categories
]);

print($layout);
