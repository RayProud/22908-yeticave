<?php

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

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt($link, $sql, $data = []) {
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt === false) {
        die(mysqli_error($link));
    }

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = null;

            if (is_int($value)) {
                $type = 'i';
            }
            else if (is_string($value)) {
                $type = 's';
            }
            else if (is_double($value)) {
                $type = 'd';
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);
    }

    return $stmt;
}

/**
 * Делает запрос в базу на поиск данных, обрабывает все ошибки и возвращает массив данных, если он есть
 *
 * @param $link mysqli Ресурс соединения
 * @param string $query SQL запрос с плейсхолдерами вместо значений
 * @param mixed ...$data данные для execute_statement
 *
 * @return array|null
 */
function execute_get_statement($link, string $query, ...$data): ?array {
    $stmt = db_get_prepare_stmt($link, $query, ...$data);
    $execution = mysqli_stmt_execute($stmt);

    if ($execution === false) {
        die('A statement execution error occured: ' . mysqli_error($link));
    }

    $response = mysqli_stmt_get_result($stmt);

    if ($response === false) {
        die('A query error occured: ' . mysqli_error($link));
    }

    if (mysqli_num_rows($response) === 0) {
        return null;
    }

    return mysqli_fetch_all($response, MYSQLI_ASSOC);
}

/**
 * Делает запрос в базу для добавления данных, обрабывает все ошибки и id сделанной записи
 *
 * @param $link mysqli Ресурс соединения
 * @param string $query SQL запрос с плейсхолдерами вместо значений
 * @param mixed ...$data данные для execute_statement
 *
 * @return int|null
 */
function execute_insert_statement($link, string $query, ?array $data): ?int {
    $stmt = db_get_prepare_stmt($link, $query, $data);

    $execution = mysqli_stmt_execute($stmt);

    if ($execution === false) {
        die('A statement execution error occured: ' . mysqli_error($link));
    }

    mysqli_stmt_get_result($stmt);

    return mysqli_insert_id($link);
}

/**
 * Делает запрос за лотом по его ID
 *
 * @param $link mysqli Ресурс соединения
 * @param $lot_id int ID искомого лота
 *
 * @return array|null
 */
function get_lot($link, int $lot_id): ?array {
    $get_lot_query = 'SELECT l.description, l.end_at, l.bet_step, l.id, l.title, l.start_price, l.image_url, c.title AS category_title, IF(MAX(b.amount) IS NOT NULL, MAX(b.amount), l.start_price) AS price
        FROM lot l
            JOIN category c
              ON l.category_id=c.id
            LEFT JOIN bet b
              ON l.id=b.lot_id
        WHERE l.id=?
        GROUP BY l.id;';

    $response = execute_get_statement($link, $get_lot_query, [$lot_id]);

    return $response ? $response[0] : $response;
}

/**
 * Делает запрос за ставками по ID лота
 *
 * @param $link mysqli Ресурс соединения
 * @param $lot_id int ID искомого лота
 *
 * @return array|null
 */
function get_bets($link, int $lot_id): ?array {
    $get_bets_query = 'SELECT b.created_at, b.amount, u.name
        FROM lot l
               JOIN bet b
                    ON b.lot_id=l.id
               JOIN user u
                    ON b.author_id=u.id
        WHERE l.id = ?
        ORDER BY b.created_at DESC;';

    return execute_get_statement($link, $get_bets_query, [$lot_id]);
}

/**
 * Делает запрос за всеми актуальными лотами
 *
 * @param $link mysqli Ресурс соединения
 *
 * @return array|null
 */
function get_all_lots($link): ?array {
    $get_newest_lots_query = 'SELECT l.id, l.title, l.start_price, l.image_url, l.end_at, c.title AS category_title
        FROM lot l
               JOIN category c
                    ON l.category_id=c.id
        WHERE l.end_at >= NOW()
        ORDER BY l.created_at DESC;';

    return execute_get_statement($link, $get_newest_lots_query);
}

/**
 * Делает запрос за лотами по категории
 *
 * @param $link mysqli Ресурс соединения
 * @param int $category_id Категория
 *
 * @return array|null
 */
function get_category_lots($link, int $category_id): ?array {
    $get_lots_query = 'SELECT l.id, l.title, l.start_price, l.image_url, l.end_at FROM lot l
        WHERE l.category_id=? AND l.end_at >= NOW()
        ORDER BY l.created_at DESC;';

    return execute_get_statement($link, $get_lots_query, [$category_id]);
}

/**
 * Делает запрос за всеми категориями
 *
 * @param $link mysqli Ресурс соединения
 *
 * @return array|null
 */
function get_categories($link): ?array {
    $get_categories_query = 'SELECT title, id FROM category';

    return execute_get_statement($link, $get_categories_query);
}

/**
 * Сохраняет лот
 *
 * @param $link mysqli Ресурс соединения
 * @param $lot array Лот
 *
 * @return int|null
 */
function save_lot($link, array $lot): ?int {
    $save_lot_query = 'INSERT INTO lot (title,description,image_url,start_price,end_at,bet_step,author_id,category_id) VALUES(?,?,?,?,?,?,?,?)';

    return execute_insert_statement($link, $save_lot_query, array_values($lot));
}

/**
 * Сохраняет ставку
 *
 * @param $link mysqli Ресурс соединения
 * @param $bet array Ставка
 *
 * @return int|null
 */
function save_bet($link, array $bet): ?int {
    $save_bet_query = 'INSERT INTO bet (amount,author_id,lot_id) VALUES(?,?,?)';

    return execute_insert_statement($link, $save_bet_query, array_values($bet));
}

/**
 * Возвращает самую большую ставку лота
 *
 * @param $link mysqli Ресурс соединения
 * @param $lot_id int Id лота
 *
 * @return int|null
 */
function get_max_bet($link, int $lot_id): ?int {
    $find_new_price_query = 'SELECT MAX(amount) as max FROM bet WHERE bet.lot_id=?';
    $res = execute_get_statement($link, $find_new_price_query, [$lot_id]);

    return array_column($res, 'max')[0] ?? null;
}

/**
 * Сохраняет пользователя
 *
 * @param $link mysqli Ресурс соединения
 * @param $user array Пользователь
 *
 * @return array|null
 */
function save_user($link, array $user): ?int {
    $save_user_query = 'INSERT INTO user (email,name,password,image_url,contacts) VALUES(?,?,?,?,?)';

    return execute_insert_statement($link, $save_user_query, array_values($user));
}

/**
 * Проверяет наличие email'а в базе
 *
 * @param $link mysqli Ресурс соединения
 * @param string $email
 *
 * @return bool
 */
function does_such_email_already_exist($link, string $email): bool {
    $find_email_query = 'SELECT * FROM user WHERE email = ?';

    $response = execute_get_statement($link, $find_email_query, [$email]);

    return !!$response;
}

/**
 * Достаёт пароль пользователя по переданному email'у
 *
 * @param $link mysqli Ресурс соединения
 * @param string $email
 *
 * @return string|null
 */
function get_hashed_password_by_email($link, string $email): ?string {
    $find_email_query = 'SELECT password FROM user WHERE email = ?';

    $response = execute_get_statement($link, $find_email_query, [$email]);

    return $response[0]['password'] ?? $response;
}

/**
 * Достаёт пользователя для сессии по переданному email'у
 *
 * @param $link mysqli Ресурс соединения
 * @param string $email
 *
 * @return array|null
 */
function get_user_by_email($link, string $email): ?array {
    $find_user_query = 'SELECT id, email, name, image_url FROM user WHERE email = ?';

    $response = execute_get_statement($link, $find_user_query, [$email]);

    return $response[0] ?: $response;
}
