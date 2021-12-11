<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 20.04.2019
 * Time: 10:58
 */

define('DBUSER', 'root');
define('DBPASS', '');
define('DBDSN', 'mysql:host=localhost;dbname=eshop;charset=utf8');


/**
 * Подключается к БД или возвращает уже созданное подключение
 * @param string $user Имя пользователя БД
 * @param string $pass Пароль пользователя БД
 * @param string $dsn строка подключения
 * @return PDO обьект PDO
 */
function getConnection(string $user = DBUSER, string $pass = DBPASS, string $dsn = DBDSN)
{
    static $connection;
    if (null === $connection) {
       try{
           $connection = new PDO($dsn, $user, $pass);
       } catch (PDOException $e){
           die('Нет соединения с БД, проверте настройки.');
       }
    }
    return $connection;
}


/**
 * Выполняет произвольный SQL запрос
 * @param string $sql
 * @param array $data
 * @param bool $showError
 * @return bool|PDOStatement
 */
function sql(string $sql, array $data = [], $showError = true)
{
//    debug([$sql, $data]);
    try {
        if (!empty($data)) {
            $stmt = getConnection()->prepare($sql);
            $done = $stmt->execute($data);
            if (!$done && $showError) {

                ob_start();
                $stmt->debugDumpParams();
                $dump = ob_get_clean();
                debug(['ErrorInfo' => $stmt->errorInfo(), 'SQL' => $sql, 'Data' => $data, 'DebugDumpParams' => $dump, 'backtrace' => debug_backtrace()]);
                die(printDebug());
            }
        } else {
            $stmt = getConnection()->query($sql);

            if (!$stmt && $showError) {
                debug(['ErrorInfo' => getConnection()->errorInfo(), 'SQL' => $sql, 'backtrace' => debug_backtrace()]);
                die(printDebug());
            }

        }
        return $stmt;
    } catch (Exception $exception) {
        debug($exception);
    }
}

/**
 * Вставляем запись в БД
 * @param string $table Имя таблицы
 * @param array $data ассоциативный массив с данными вида ['имя-столбца' => 'значение']
 * @return bool|PDOStatement
 */
function insert(string $table, array $data)
{

    $string = 'INSERT INTO ' . $table . ' (';

    $tmpVals = '';
    $tmpArr = [];
    foreach ($data as $k => $v) {
        $string .= $k . ',';
        $tmpVals .= ':' . $k . ',';
        $tmpArr[':' . $k] = $v;
    }

    $string = rtrim($string, ',');
    $tmpVals = rtrim($tmpVals, ',');
    $string .= ') VALUES (' . $tmpVals . ')';

    return sql($string, $tmpArr);
}

/**
 * Возвращает все записи из таблыцы или false в случае ошибки
 * @param string $table Имя таблицы
 * @param string $limit
 * @param bool $fetchAll
 * @return bool|PDOStatement
 */
function selectAll(string $table, $limit = null, $fetchAll = true, $order = SORT_ASC, $order_column = 'id')
{
    $string = 'SELECT * FROM ' . $table;
    $string .= ' ORDER BY ' . $order_column . ' ';
    $string .= $order == SORT_ASC ? 'ASC' : 'DESC';
    $string .= $limit != null ? ' LIMIT ' . $limit : '';

    $stmt = sql($string);
    return $stmt ? $fetchAll ? $stmt->fetchAll(PDO::FETCH_ASSOC) : $stmt->fetch(PDO::FETCH_ASSOC) : $stmt;
}

/**
 * Возвращает из выбранной таблицы записи совпадающие с условиями
 * @param string $table Имя таблицы
 * @param array $wheres массив с условиями значений в формате ['имя-столбца' => 'значение']
 * @param string|array @columns из каких столбов нужны данные
 * @param string $limit
 * @param bool $fetchAll
 * @return bool|PDOStatement
 */
function selectColumnsWhere(string $table, array $wheres, $columns = '*', $limit = null, $fetchAll = true, $order = SORT_ASC, $order_column = 'id')
{
    $string = 'SELECT ';

    if (is_string($columns)) {
        $string .= $columns;
    } elseif (is_array($columns)) {
        foreach ($columns as $column) {
            $string .= $column . ', ';
        }
    } else {
        $string .= '*';
    }
    $string = rtrim($string, ', ');
    $string .= ' FROM ' . $table;

    $where = buildWhere($wheres);
    $string .= $where['str'];


    $string .= ' ORDER BY ' . $order_column . ' ';
    $string .= $order == SORT_ASC ? 'ASC' : 'DESC';
    $string .= $limit != null ? ' LIMIT ' . $limit : '';

    $stmt = sql($string, $where['bindings']);
    return $stmt ? $fetchAll ? $stmt->fetchAll(PDO::FETCH_ASSOC) : $stmt->fetch(PDO::FETCH_ASSOC) : $stmt;

}

/**
 * Обновляет запись в таблице
 * @param $table
 * @param $data
 * @param $where
 * @return bool|PDOStatement
 */
function update($table, $data, $wheres)
{
    $sql = 'UPDATE ' . $table . ' SET ';

    $bindings = [];
    foreach ($data as $k => $v) {
        $sql .= $k . "=:{$k}, ";
        $bindings[':' . $k] = $v;
    }
    $sql = substr($sql, 0, -2);

    $where = buildWhere($wheres);
    $sql .= $where['str'];

    $bindings = array_merge($bindings, $where['bindings']);

    return sql($sql, $bindings);
}

/**
 * Возвращает последний ID выбранной таблицы
 * @param string $table
 * @return int
 */
function tableLastID(string $table)
{
    $result = sql('SELECT max(id) as lastID FROM ' . $table)->fetch(PDO::FETCH_ASSOC)['lastID'];
    return intval($result);
}

/**
 * Возвращает записи из таблицы B связанные с записью из таблицы A
 * @param $tableA Таблица А
 * @param $tableB Таблица В
 * @param $id Запись в таблице А
 * @return bool|array
 */
function getConnectedRows($tableA, $tableB, $id, $where = [])
{
    if (tableExists($tableA . '_' . $tableB)) {
        $connections = selectColumnsWhere($tableA . '_' . $tableB, [$tableA . '_id' => $id], '*', null, true, SORT_ASC, $tableA . '_id');
        if (!empty($connections)) {
            $rows = [];
            foreach ($connections as $connection) {
                $wheres = $where + ['id' => $connection[$tableB . '_id']];
                $rows[] = selectColumnsWhere($tableB, $wheres, '*', '0,1', false, SORT_ASC, 'id');
            }
        }
    } else {
        $wheres = $where + [$tableA.'_id' => $id];
        $rows = selectColumnsWhere($tableB, $wheres);
    }

    if(!empty($rows)){
        return $rows;
    }

    return false;
}

/**
 * Удаляет строки подходящие по условию из таблицы
 * @param $table
 * @param $wheres
 * @return bool|PDOStatement
 */
function delete($table, $wheres)
{
    $sql = 'DELETE FROM ' . $table;

    $where = buildWhere($wheres);

    $sql .= $where['str'];

    return sql($sql, $where['bindings']);
}

/**
 * Удаляет из таблицы B все соедененные строки с таблицой A
 * @param $tableA
 * @param $tableB
 * @param $id
 * @return bool
 */
function deleteConnectedRows($tableA, $tableB, $id)
{
    $rows = getConnectedRows($tableA, $tableB, $id);
    if ($rows) {
        $error = false;
        foreach ($rows as $row) {
            $stmt = delete($tableB, ['id' => $row['id']]);
            $error = boolval(intval($stmt->errorCode()));
            if ($error) {
                break;
            }
        }
        debug($error);
        if ($error) {
            return true;
        }
    }
    return false;
}

/**
 * Возвращает количество строк совпадающих с условием
 * @param $table
 * @param array $wheres
 * @return int
 */
function countRows($table, $wheres = [])
{
    $sql = 'SELECT COUNT(*) as length FROM ' . $table;
    if (!empty($wheres)) {

        $where = buildWhere($wheres);
        $sql .= $where['str'];

        return intval(sql($sql, $where['bindings'])->fetch(PDO::FETCH_ASSOC)['length']);
    }
    return intval(sql($sql)->fetch(PDO::FETCH_ASSOC)['length']);

}

/**
 * Полностью очищает таблицу
 * @param $table
 * @return bool|PDOStatement
 */
function wipeTable($table)
{
    return sql("TRUNCATE `{$table}`");
}

/**
 * Строит блок WHERE для sql-запроса
 * @param $array
 * @return array
 */
function buildWhere($array)
{
    $str = ' WHERE ';
    foreach ($array as $key => $value) {
        if (!is_array($value)) {
            $str .= $key . "=:{$key} AND ";
            $bindings[':' . $key] = $value;
        } else {
            $str .= $key . ' ' . $value['operator'] . ':' . $key . ' AND ';
            $bindings[':' . $key] = $value['condition'];
        }
    }
    $str = substr($str, 0, -5);
    return ['str' => $str, 'bindings' => $bindings];
}


/**
 * Возвращает все настройки проэкта из БД
 * @return bool|PDOStatement
 */
function settings()
{
    static $settings;
    if (is_null($settings)) {
        $settings = selectAll('settings');
    }
    return $settings;
}

/**
 * Возвращает необходимую настройку из бд по имени
 * @param $name
 * @return mixed
 */
function getSetting($name)
{
    return settings()[array_search($name, array_column(settings(), 'name'))]['setting'];
}

function tableExists($table)
{
    try {
        $result = sql("SELECT 1 FROM $table LIMIT 1", [], false);

    } catch (Exception $e) {
        return FALSE;
    }
    return $result !== FALSE;
}