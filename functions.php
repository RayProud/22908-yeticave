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
 * Checks if a value is not null and not empty
 *
 * @param any $value
 *
 * @return bool
 */
function not_null($value): bool {
    return $value !== null && !empty($value);
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

/**
 * Checks if there is a file with a given field name
 *
 * @param any $_
 *
 * @param string $photo_field_name
 *
 * @return bool
 */
function has_image($_, string $photo_field_name): bool {
    $file = $_FILES[$photo_field_name];

    return !!$file['size'];
}

/**
 * Checks if a file's mime-type is allowed
 *
 * @param $_
 *
 * @param string $photo_field_name
 *
 * @return bool
 */
function has_correct_mime_type($_, string $photo_field_name): bool {
    $allowed_image_types = ['image/jpg', 'image/jpeg', 'image/png'];

    return in_array($_FILES[$photo_field_name]['type'], $allowed_image_types, true);
}

/**
 * Check POST data from add-lot page
 *
 * @return array
 */
function validate_lot(): array {
    $lot_rules = [
        'lot-name' => [
            'not_null' => 'Введите наименование лота'
        ],
        'category' => [
            'not_null' => 'Выберите категорию'
        ],
        'message' => [
            'not_null' => 'Напишите описание лота'
        ],
        'lot-photo' => [
            'has_image' => 'Добавьте изображение',
            'has_correct_mime_type' => 'Допустимые форматы файлов: jpg, jpeg, png'
        ],
        'lot-rate' => [
            'not_null' => 'Введите начальную цену',
            'is_positive_number' => 'Начальная цена должна быть числом больше нуля',
        ],
        'lot-step' => [
            'not_null' => 'Введите шаг ставки',
            'is_positive_int' => 'Шаг ставки должен быть целым числом больше ноля',
        ],
        'lot-date' => [
            'not_null' => 'Введите дату завершения торгов',
            'is_date_correct_and_later_than_current_day' => 'Дата завершения должна быть больше текущей даты, хотя бы на один день',
        ]
    ];

    $found_errors = [];

    foreach ($lot_rules as $form_name => $tests) {
        $current_value = $_POST[$form_name] ?? null;
        $found_errors[$form_name] = [];

        foreach ($tests as $validate_func => $error_msg) {
            if (!$validate_func($current_value, $form_name)) {
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

/**
 * Check POST data from sign-up page
 *
 * @return array
 */
function validate_user(): array {
    $user_rules = [
        'email' => [
            'not_null' => 'Введите наименование лота',
            'is_email' => 'Введите корректный email'
        ],
        'password' => [
            'not_null' => 'Выберите категорию'
        ],
        'contacts' => [
            'not_null' => 'Напишите описание лота'
        ],
        'image_url' => [
            'has_image' => 'Добавьте изображение',
            'has_correct_mime_type' => 'Допустимые форматы файлов: jpg, jpeg, png'
            // добавить необязательность
        ],
        'name' => [
            'not_null' => 'Введите начальную цену',
            'is_positive_number' => 'Начальная цена должна быть числом больше нуля',
        ]
    ];

    // отдельная переменная необязательных полей?

    $found_errors = [];

    foreach ($user_rules as $form_name => $tests) {
        $current_value = $_POST[$form_name] ?? null;
        $found_errors[$form_name] = [];

        foreach ($tests as $validate_func => $error_msg) {
            if (!$validate_func($current_value, $form_name)) {
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

/**
 * Moves a files to the img folder
 *
 * @param string $photo_name
 */
function move_photo_to_img(string $photo_name) {
    $path = $_FILES[$photo_name]['name'];
    $ext = pathinfo($path, PATHINFO_EXTENSION);

    $filename = uniqid() . '.' . $ext;

    $file_path = 'img/' . $filename;

    move_uploaded_file($_FILES[$photo_name]['tmp_name'], $file_path);
}
