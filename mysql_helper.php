<?php

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

function get_lot($link, $id) {
    if (!is_int($id)) {
        return null;
    }

    $get_lot_query = 'SELECT l.description, l.end_at, l.bet_step, l.id, l.title, l.start_price, l.image_url, c.title AS category_title
        FROM lot l
               JOIN category c
                    ON l.category_id=c.id
        WHERE l.id = ?;';

    $stmt = db_get_prepare_stmt($link, $get_lot_query, [$id]);
    mysqli_stmt_execute($stmt);
    $response = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($response) === 0) {
        return null;
    }

    if ($response === false) {
        die('A query error occured: ' . mysqli_error($link));
    }

    return mysqli_fetch_assoc($response);
}

function get_all_lots($link) {
    $get_newest_lots_query = 'SELECT l.id, l.title, l.start_price, l.image_url, c.title AS category_title
        FROM lot l
               JOIN category c
                    ON l.category_id=c.id
        WHERE l.end_at >= NOW()
        ORDER BY l.created_at DESC;';

    $stmt = db_get_prepare_stmt($link, $get_newest_lots_query);
    mysqli_stmt_execute($stmt);
    $response = mysqli_stmt_get_result($stmt);

    if ($response === false) {
        die('A query error occured: ' . mysqli_error($link));
    }

    return mysqli_fetch_all($response, MYSQLI_ASSOC);
}

function get_categories($link) {
    $get_categories_query = 'SELECT title FROM category';

    $stmt = db_get_prepare_stmt($link, $get_categories_query);
    mysqli_stmt_execute($stmt);
    $response = mysqli_stmt_get_result($stmt);

    if ($response === false) {
        die('A query error occured: ' . mysqli_error($link));
    }

    return array_column(mysqli_fetch_all($response, MYSQLI_ASSOC), 'title');
}

