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
function include_template(string $name, array $data): string {
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
    $formatted_price = number_format(ceil($price), 0, '.', ' ');

    return $formatted_price . ' <b class="rub">р</b>';
}
