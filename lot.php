<?php
require_once('./init.php');

if (!isset($_GET['lot']) || !is_numeric($_GET['lot'])) {
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

$lot_id = (int) $_GET['lot'];
$lot = get_lot($link, $lot_id);

$user_id = $_SESSION['user']['id'] ?? null;
$lot_page_data = [
    'lot' => $lot,
    'price' => $lot['price'] ?? $lot['start_price'],
    'show_bidding' => isset($_SESSION['user']) && $lot['author_id'] !== $user_id && get_time_till_date($lot["end_at"]) !== null
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
        $max_bet = get_max_bet($link, $lot_id);
        $lot_page_data['price'] = $max_bet;
    }
}

$bets = get_bets($link, $lot_id) ?? [];

foreach ($bets as $bet) {
    if ($bet['author_id'] === $user_id) {
        $lot_page_data['show_bidding'] = FALSE;
    }
}

$max_bet = $bets ? max(array_column($bets, 'amount')) : null;

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
    'categories' => $full_categories
]);

print($layout);
