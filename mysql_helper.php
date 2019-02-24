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
 * Делает запрос в базу, обрабывает все ошибки и возвращает массив данных, если он есть
 *
 * @param $link mysqli Ресурс соединения
 * @param string $query SQL запрос с плейсхолдерами вместо значений
 * @param mixed ...$data данные для execute_statement
 *
 * @return array|null
 */
function execute_statement($link, string $query, ...$data): ?array {
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
 * Делает запрос за лотом по его ID
 *
 * @param $link mysqli Ресурс соединения
 * @param $lot_id int ID искомого лота
 *
 * @return array|null
 */
function get_lot($link, int $lot_id): ?array {
    $get_lot_query = 'SELECT l.description, l.end_at, l.bet_step, l.id, l.title, l.start_price, l.image_url, c.title AS category_title
        FROM lot l
               JOIN category c
                    ON l.category_id=c.id
        WHERE l.id = ?;';

    $response = execute_statement($link, $get_lot_query, [$lot_id]);

    return $response ? $response[0] : $response;
}

/**
 * Делает запрос за всеми актуальными лотами
 *
 * @param $link mysqli Ресурс соединения
 *
 * @return array|null
 */
function get_all_lots($link): ?array {
    $get_newest_lots_query = 'SELECT l.id, l.title, l.start_price, l.image_url, c.title AS category_title
        FROM lot l
               JOIN category c
                    ON l.category_id=c.id
        WHERE l.end_at >= NOW()
        ORDER BY l.created_at DESC;';

    return execute_statement($link, $get_newest_lots_query);
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

    $response = execute_statement($link, $get_categories_query);

    return $response;
}

/**
 * Сохраняет лот
 *
 * @param $link mysqli Ресурс соединения
 *
 * @return array|null
 */
function save_lot($link, $title, $description, $image_url, $start_price, $end_at, $bet_step, $author_id, $category_id): ?int {
    $get_categories_query = 'INSERT INTO lot (title,description,image_url,start_price,end_at,bet_step,author_id,category_id) VALUES(?,?,?,?,?,?,?,?)';

    $stmt = db_get_prepare_stmt($link, $get_categories_query, [$title, $description, $image_url, $start_price, $end_at, $bet_step, $author_id, $category_id]);
    $execution = mysqli_stmt_execute($stmt);

    if ($execution === false) {
        die('A statement execution error occured: ' . mysqli_error($link));
    }

    mysqli_stmt_get_result($stmt);

    return mysqli_insert_id($link);
}
