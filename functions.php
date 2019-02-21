<?php

/**
 * Template requirer
 *
 * @param string $name File name including its extension
 *
 * @param array $data Associative array of data which will be passed to the template
 *
 * @return string HTML including data from passed $data
 */
function include_template(string $name, array $data = []): string {
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}

/**
 * Format a price in Russian notation and append a RUR symbol
 *
 * @param float $price
 *
 * @return string
 */
function format_price(float $price): string {
    $formatted_price = number_format($price, 2, '.', ' ');

    return $formatted_price . ' <b class="rub">р</b>';
}

/**
 * Returns hours and minutes till midnight in HH:mm format
 *
 * @return string
 */
function get_time_till_midnight(): string {
    $now_date = date_create();
    $tomorrow_midnight_date = date_create('tomorrow midnight');

    return date_interval_format(date_diff($now_date, $tomorrow_midnight_date), '%H:%I');
}

/**
 * Returns null if the date's in the past or returns days, hours and minutes till a given date
 *
 * @return string|null
 */
function get_time_till_date(string $end_date): ?string {
    $now_date = date_create();
    $end_date = date_create($end_date);

    return $now_date > $end_date
        ? null
        : date_interval_format(date_diff($now_date, $end_date), '%dд. %H:%I');

}

/**
 * Connect to a db and configure connection settings
 *
 * @return mysqli
 */
function get_connection() {
    $link = mysqli_init();

    if (!$link) {
        die('Error link');
    }

    if (!mysqli_options($link, MYSQLI_OPT_INT_AND_FLOAT_NATIVE, 1)) {
        die('Error mysqli_options');
    }

    if (!mysqli_real_connect($link, '127.0.0.1', 'root', '', 'yeticave')) {
        die('A db connection error occured: ' . mysqli_connect_error());
    }

    mysqli_set_charset($link, "utf8");

    return $link;
}
