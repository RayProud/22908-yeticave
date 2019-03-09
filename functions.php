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
 * Returns null if the date's in the past or returns days, hours and minutes till a given date
 *
 * @param string $end_date
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
 * Returns null if the date's in the past or returns only hours and minutes till a given date
 *
 * @param string $end_date
 *
 * @return string|null
 */
function get_time_till_date_in_hours_and_minutes(string $end_date): ?string {
    $now_date = date_create();
    $end_date = date_create($end_date);

    if ($now_date > $end_date) {
        return null;
    }

    $hours = date_interval_format(date_diff($now_date, $end_date), '%h');
    $days = date_interval_format(date_diff($now_date, $end_date), '%a');
    $minutes = date_interval_format(date_diff($now_date, $end_date), '%I');
    $total_hours = $hours + ($days * 24);
    $total_hours = $total_hours < 10 ? "0" . $total_hours : $total_hours;

    return $total_hours . ":" . $minutes;
}

/**
 * Returns human format time from now
 *
 * @param string $date
 *
 * @return string
 */
function get_human_time_from_now(string $date): string {
    $now_date = date_create();
    $end_date = date_create($date);
    $abs_days_left = date_interval_format(date_diff($now_date, $end_date), "%a");

    if ($abs_days_left < 1 && date_interval_format(date_diff($now_date, $end_date), "%i") < 1) {
        return 'меньше минуты';
    }

    if ($abs_days_left >= 1) {
        $days = (int) date_interval_format(date_diff($now_date, $end_date), '%a');

        if ($days === 1) {
            return 'день';
        }

        $test_num = $days % 100;

        if ($test_num >= 5 && $test_num <= 20) {
            return $days . ' дней';
        }

        $test_num %= 10;

        if ($test_num === 1) {
            return $days . ' день';
        }

        if ($test_num >= 2 && $test_num <= 4) {
            return $days . ' дня';
        }

        return $days . ' дней';
    }

    if ($abs_days_left < 1 && date_interval_format(date_diff($now_date, $end_date), "%H") < 1) {
        $minutes = (int) date_interval_format(date_diff($now_date, $end_date), '%i');

        if ($minutes === 1) {
            return 'час';
        }

        $test_num = $minutes % 100;

        if ($test_num >= 5 && $test_num <= 20) {
            return $minutes . ' минут';
        }

        $test_num %= 10;

        if ($test_num === 1) {
            return $minutes . ' минута';
        }

        if ($test_num >= 2 && $test_num <= 4) {
            return $minutes . ' минуты';
        }

        return $minutes . ' минут';
    }

    if ($abs_days_left < 1 && date_interval_format(date_diff($now_date, $end_date), "%H") < 24) {
        $hours = (int) date_interval_format(date_diff($now_date, $end_date), '%h');

        if ($hours === 1) {
            return 'час';
        }

        $test_num = $hours % 100;

        if ($test_num >= 5 && $test_num <= 20) {
            return $hours . ' часов';
        }

        $test_num %= 10;

        if ($test_num === 1) {
            return $hours . ' час';
        }

        if ($test_num >= 2 && $test_num <= 4) {
            return $hours . ' часа';
        }

        return $hours . ' часов';
    }

    return date_interval_format(date_diff($now_date, $end_date), '%dд. %H:%I');
}

/**
 * Checks if a value is not null and not empty
 *
 * @param any $value
 *
 * @return bool
 */
function not_null($value): bool {
    if (is_string($value)) {
        $new_value = trim($value, " \t\n\r");
        return $new_value !== '';
    }

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

    return $lot_date > $now && date_interval_format(date_diff($lot_date, $now), "%a") >= 1;
}

/**
 * Checks if there is a file with a given field name
 *
 * @param string $photo_field_name
 *
 * @return bool
 */
function does_file_exist(string $photo_field_name): bool {
    $file = $_FILES[$photo_field_name];

    return !!$file['size'];
}

/**
 * Checks if a file's mime-type is allowed
 *
 * @param string $photo_field_name
 *
 * @return bool
 */
function has_correct_mime_type(string $photo_field_name): bool {
    $allowed_image_types = ['image/jpg', 'image/jpeg', 'image/png'];

    if (!isset($_FILES[$photo_field_name]) || !not_null($_FILES[$photo_field_name]['tmp_name'])) {
        return false;
    }

    return in_array(mime_content_type($_FILES[$photo_field_name]['tmp_name']), $allowed_image_types, true);
}

/**
 * Checks if a value is email-like
 *
 * @param any $value
 *
 * @return bool
 */
function is_email_like($value): bool {
    if (is_string($value)) {
        $value = trim($value, " \t\n\r");
    }

    return filter_var($value, FILTER_VALIDATE_EMAIL);
}

/**
 * Checks if email of a given value doens't exist in the db
 *
 * @param any $value
 *
 * @return bool
 */
function is_email_unique($value): bool {
    if (is_string($value)) {
        $value = trim($value, " \t\n\r");
    }

    return !does_such_email_already_exist($GLOBALS['link'], $value);
}

/**
 * Checks if email of a given value exists in the db
 *
 * @param any $value
 *
 * @return bool
 */
function does_email_exist($value): bool {
    if (is_string($value)) {
        $value = trim($value, " \t\n\r");
    }

    return does_such_email_already_exist($GLOBALS['link'], $value);
}

/**
 * Check POST bet data from lot page
 *
 * @param $lot
 *
 * @return array
 */
function validate_bet(array $lot): array {
    $bet_post_rules = [
        'cost' => [
            'not_null' => 'Введите ставку',
            'is_positive_int' => 'Ставка должна быть целым положительным числом'
        ]
    ];

    if (count(validate_post_data($bet_post_rules)) > 0) {
        return validate_post_data($bet_post_rules);
    }

    $highest_price = $lot["price"] ?? $lot["start_price"];

    $minimum_bet = $highest_price + $lot["bet_step"];

    if ($_POST['cost'] < $minimum_bet) {
        return ['cost' => 'Ставка должна быть больше суммы цены и минимальной ставки'];
    }

    return [];
}

/**
 * Check POST data from add-lot page
 *
 * @return array
 */
function validate_lot(): array {
    $lot_post_rules = [
        'lot-name' => [
            'not_null' => 'Введите наименование лота'
        ],
        'category' => [
            'not_null' => 'Выберите категорию'
        ],
        'message' => [
            'not_null' => 'Напишите описание лота'
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

    $lot_files_rules = [
        'lot-photo' => [
            'does_file_exist' => 'Добавьте изображение',
            'has_correct_mime_type' => 'Допустимые форматы файлов: jpg, jpeg, png'
        ]
    ];

    return array_merge(validate_post_data($lot_post_rules), validate_files_data($lot_files_rules));
}

/**
 * Checks POST data from sign-up page
 *
 * @return array
 */
function validate_user(): array {
    $user_post_rules = [
        'name' => [
            'not_null' => 'Введите имя'
        ],
        'email' => [
            'not_null' => 'Введите e-mail',
            'is_email_unique' => 'Пользователь с таким e-mail уже существует',
            'is_email_like' => 'Введите корректный email'
        ],
        'password' => [
            'not_null' => 'Введите пароль'
        ],
        'contacts' => [
            'not_null' => 'Напишите как с вами связаться'
        ]
    ];

    $user_files_rules = [
        'avatar' => [
            'has_correct_mime_type' => 'Допустимые форматы файлов: jpg, jpeg, png'
        ]
    ];

    return array_merge(validate_post_data($user_post_rules), validate_files_data($user_files_rules));
}

/**
 * Checks POST data from login page
 *
 * @return array
 */
function validate_login(): array {
    $user_post_rules = [
        'email' => [
            'not_null' => 'Введите e-mail',
            'is_email_like' => 'Введите корректный email'
        ],
        'password' => [
            'not_null' => 'Введите пароль'
        ]
    ];

    if (count(validate_post_data($user_post_rules)) > 0) {
        return validate_post_data($user_post_rules);
    }

    $trimmed_email = trim($_POST['email'], " \t\n\r");

    $found_pass = get_hashed_password_by_email($GLOBALS['link'], $trimmed_email);

    if(!password_verify($_POST['password'], $found_pass) || !does_email_exist($trimmed_email)) {
        return ['incorrect_data' => 'Вы ввели неверный email/пароль'];
    }

    return [];
}

/**
 * Checks FILES using a given scheme
 *
 * @param array $scheme
 *
 * @return array Found errors
 */
function validate_files_data(array $scheme): array {
    $found_errors = [];

    foreach ($scheme as $form_name => $tests) {
        $is_value_optional = !does_file_exist($form_name) && !in_array('does_file_exist', array_keys($tests), true);

        if ($is_value_optional) {
            continue;
        }

        $found_errors[$form_name] = [];

        foreach ($tests as $validate_func => $error_msg) {
            if (!$validate_func($form_name)) {
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
 * Checks POST data using a given scheme
 *
 * @param array $scheme
 *
 * @return array Found errors
 */
function validate_post_data(array $scheme): array {
    $found_errors = [];

    foreach ($scheme as $form_name => $tests) {
        $current_value = $_POST[$form_name] ?? null;

        $is_value_optional = !not_null($current_value) && !in_array('not_null', array_keys($tests), true);

        if ($is_value_optional) {
            continue;
        }

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

/**
 * Moves a files to the img folder
 *
 * @param string $photo_name
 *
 * @return null|string
 */
function move_photo_to_img(string $photo_name) {
    if (!does_file_exist($photo_name)) {
        return null;
    }

    $path = $_FILES[$photo_name]['name'];
    $ext = pathinfo($path, PATHINFO_EXTENSION);

    $filename = uniqid() . '.' . $ext;

    $file_path = 'img/' . $filename;

    move_uploaded_file($_FILES[$photo_name]['tmp_name'], $file_path);

    return $file_path;
}
