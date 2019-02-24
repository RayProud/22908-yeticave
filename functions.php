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
 * Checks if a value is not empty
 *
 * @param any $value
 *
 * @return bool
 */
function not_empty($value): bool {
    return $value !== null;
}

/**
 * Checks if a value is a positive number
 *
 * @param any $value
 *
 * @return bool
 */
function is_positive_number($value): bool {
    return is_numeric($value) && $value > 0;
}

/**
 * Checks if a value is a positive integer number
 *
 * @param any $value
 *
 * @return bool
 */
function is_positive_int($value): bool {
    $converted_value = (int) $value;

    return is_int($converted_value) && $converted_value > 0;
}

/**
 * Checks if a value can be converted to a date and the date is more than one day ahead of now
 *
 * @param any $value
 *
 * @return bool
 */
function is_date_correct_and_later_than_current_day($value): bool {
    $now = date_create('today');
    $lot_date = date_create($value);

    return $lot_date > $now && date_interval_format(date_diff($lot_date, $now), "%d") >= 1;
}


function has_image(): bool {
    $file = $_FILES['lot-photo'];

    return !!$file['size'];
}

function has_correct_mime_type(): bool {
    $allowed_image_types = ['image/jpg', 'image/jpeg', 'image/png'];

    return in_array($_FILES['lot-photo']['type'], $allowed_image_types);
}

/**
 * Check POST data from add-lot page
 *
 * @return array
 */
function validate_lot(): array {
    $lot_rules = [
        'lot-name' => [
            'not_empty' => 'Введите наименование лота'
        ],
        'category' => [
            'not_empty' => 'Выберите категорию'
        ],
        'message' => [
            'not_empty' => 'Напишите описание лота'
        ],
        'lot-photo' => [
            'has_image' => 'Добавьте изображение',
            'has_correct_mime_type' => 'Допустимые форматы файлов: jpg, jpeg, png'
        ],
        'lot-rate' => [
            'not_empty' => 'Введите начальную цену',
            'is_positive_number' => 'Начальная цена должна быть числом больше нуля',
        ],
        'lot-step' => [
            'not_empty' => 'Введите шаг ставки',
            'is_positive_int' => 'Шаг ставки должен быть целым числом больше ноля',
        ],
        'lot-date' => [
            'not_empty' => 'Введите дату завершения торгов',
            'is_date_correct_and_later_than_current_day' => 'Дата завершения должна быть больше текущей даты, хотя бы на один день',
        ]
    ];

    $found_errors = [];

    foreach ($lot_rules as $form_name => $tests) {
        $current_value = isset($_POST[$form_name]) ? $_POST[$form_name] : null;
        $found_errors[$form_name] = [];

        foreach ($tests as $validate_func => $error_msg) {
            if (!$validate_func($current_value)) {
                array_push($found_errors[$form_name], $error_msg);
            }
        }

        if (count($found_errors[$form_name]) === 0) {
            unset($found_errors[$form_name]);
        } else {
            $found_errors[$form_name] = implode('; ', $found_errors[$form_name]);
        }
    }

    return $found_errors;
}
