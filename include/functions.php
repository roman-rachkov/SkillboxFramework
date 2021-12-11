<?php
/**
 * Добавляет в сессию данные дебага, для последующего удобного вывода
 * @param mixed $var переменная
 * @param bool $die остановить ли скрипт
 */
function debug($var, $explode = false)
{
    if (DEBUG) {
        $tmp = debug_backtrace()[0];
        $backTrace = $tmp['file'] . ':' . $tmp['line'];
        ob_start();
        echo '<hr>';
        echo $backTrace;
        echo '<hr>';
        echo '<pre>';

        if (is_array($var) && $explode) {
            foreach ($var as $key => $value) {
                var_dump([$key => $value]);
                echo '<hr>';
            }
        } else {
            var_dump($var);
        }

        echo '</pre><hr>';
        $_SESSION['debug'][] = ob_get_clean();
    }
}

/**
 * Вызывает отладку и сразу же умерает
 * @param $var
 * @param bool $explode
 */
function dd($var, $explode = false){
    debug($var, $explode);
    die(printDebug());
}

/**
 * Выводит отладочную информацию в удобнов виде
 */
function printDebug()
{
    if (DEBUG && !empty($_SESSION['debug'])) :?>
        <div class="debug container">
            <?php foreach ($_SESSION['debug'] as $debug) : ?>
                <?= $debug ?>
            <?php endforeach; ?>

        </div>
        <?php
        unset($_SESSION['debug']);
    endif;
}

/**
 * Перенаправляет запрос по указанному пути
 * @param string $path
 */
function redirect($path = '/')
{
    header('Location: ' . $path);
    die();
}

/**
 * Проверяет это AJAX запрос или нет
 * @return bool
 */
function isAjax()
{
    return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && (strtolower(getenv('HTTP_X_REQUESTED_WITH')) === 'xmlhttprequest'));
}

/**
 * Возвращает полный урл страницы
 * @return string
 */
function getUri()
{
    $url = 'http' . ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 's' : '') . '://';
    $url = $url . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
    return $url;
}

/**
 * Возвращает строку запроса страницы
 * @return mixed
 */
function getFullRequest()
{
    return $_SERVER['REQUEST_URI'];
}

/**
 * Возвращает путь до страницы в адресной строке
 * @return string
 */
function getPath()
{
    return rtrim(parse_url(getUri(), PHP_URL_PATH), '/');
}

/**
 * Ищет маршрут в массиве роутов и возвращает роут если таковой есть иначе возвращает false
 * @param $routeArray
 * @return bool
 */
function route($routeArray)
{
    debug($routeArray);
    foreach ($routeArray as $pattern => $route) {
        if (preg_match("#$pattern#i", trim(getPath(), '/'), $matches)) {
            foreach ($matches as $k => $v) {
                if (is_string($k)) {
                    $route[$k] = $v;
                }
            }
            return $route;
        }
    }
    return false;
}

/**
 * Возвращает слово в правильном склонении в зависимости от числителя
 * @param $num
 * @param $titles
 * @return string
 */
function declOfNum($num, $titles)
{
    $cases = array(2, 0, 1, 1, 1, 2);

    return $titles[($num % 100 > 4 && $num % 100 < 20) ? 2 : $cases[min($num % 10, 5)]];
}

/**
 * Проверяет на длинну строку, и если она длиннее задоноq велечины, возвращает сокращенную версию
 * @param string $title строка
 * @param int $length максимальная длина
 * @return string
 */
function shortString($title, $length = 15)
{
    return mb_strlen($title) > $length ? mb_substr($title, 0, $length - 3) . '...' : $title;
}

/**
 * Подключает виджеты из папки widgets
 */
function getWidgets()
{
    $dir = scandir(INCLUDE_PATH . 'widgets');
    foreach ($dir as $cat) {
        if ($cat == '.' || $cat == '..') {
            continue;
        }
        $indexWidget = INCLUDE_PATH . 'widgets' . DIRECTORY_SEPARATOR . $cat . DIRECTORY_SEPARATOR . 'index.php';
        if (is_file($indexWidget)) {
            require_once $indexWidget;
        }
    }
}

/**
 * Устанавливает в строке пути файловой системы нормальные разделители директорий
 * @param $str
 * @return mixed
 */
function setNormalSleshes($str)
{
    return str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $str);
}

/**
 * Устанавливает в строке пути адреса страницы веб разделители
 * @param $str
 * @return mixed
 */
function setWebSleshes($str)
{
    return str_replace(array('/', '\\'), '/', $str);
}

/**
 * Сортирует массив в указанном порядке
 * @param $array
 * @param string $key
 * @param int $sort
 * @return array
 */
function arraySort($array, $key = 'sort', $sort = SORT_ASC)
{
    usort($array, function ($a, $b) use ($key, $sort) {
        return $sort === SORT_ASC ? $a[$key] <=> $b[$key] : $b[$key] <=> $a[$key];
    });
    return $array;
}

/**
 * Рекурсивный вызов in_array для многомерных массивов
 * @param $needle
 * @param $haystack
 * @param bool $strict
 * @return bool
 */
function in_array_r($needle, $haystack, $strict = false) {
    foreach ($haystack as $item) {
        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
            return true;
        }
    }

    return false;
}

/**
 * @param $page
 * @return array
 */
 function getScript($page){
    $scripts = [];
    $pattern = "#<script.*?>.*?</script>#si";
    preg_match_all($pattern, $page, $scripts);
    if(!empty($scripts)){
        $page = preg_replace($pattern, '', $page);
    }
    return ['page' => $page, 'scripts' => $scripts[0]];
}

/**
 * @param $route
 * @param $content массив данных
 * Подключает файл шаблона страницы если он существует
 * @return bool|array
 */
function getTemplate($route, $content)
{
    $prefix = !(empty($route['prefix'])) ? $route['prefix'] . DIRECTORY_SEPARATOR : '';
    $template = !empty($route['template']) ? $route['template'] : $route['controller'];
    $path = DIRECTORY_SEPARATOR . $prefix . $template . '.php';
    $path = INCLUDE_PATH . 'templates' . setNormalSleshes($path);

    if (file_exists($path)) {
        if (is_array($content)) {
            extract($content);
        }
        printErrors();
        printMessages();

        ob_start();
        require_once $path;

        $page = getScript(ob_get_clean());
        return $page;

    } else {
        setError("Шаблон " . $path . " не найден");

        printErrors();
        printMessages();
    }
    return false;

}

/**
 * Подключает файл контроллера страницы если он существует
 * @param $route
 * @return mixed
 */
function getController($route)
{
    $prefix = !(empty($route['prefix'])) ? $route['prefix'] . DIRECTORY_SEPARATOR : '';
    $path = DIRECTORY_SEPARATOR . $prefix . $route['controller'] . '.php';
    $path = INCLUDE_PATH . 'controllers' . setNormalSleshes($path);

    $tmp = explode(DIRECTORY_SEPARATOR, $path);
    $tmpPath = '';
    for ($i = 0; $i < count($tmp) - 1; $i++) {
        $tmpPath .= $tmp[$i] . DIRECTORY_SEPARATOR;
    }
    //Подключаем общий файл раздела, если он сущевствует
    if (file_exists($tmpPath . DIRECTORY_SEPARATOR . 'main.php')) {
        require_once $tmpPath . DIRECTORY_SEPARATOR . 'main.php';
    } else if (file_exists($tmpPath . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'main.php')) {
        require_once $tmpPath . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'main.php';
    }
    debug($path);
    //Подключаем контроллер
    if (file_exists($path)) {
        return require_once $path;
    } else {
//        debug($route);
//        die(printDebug());
        redirect('/404');
    }

}

/**
 * Проверяет является ли файл картинкой
 * @param string $filePath
 * @return bool
 */
function isImage(string $filePath)
{
    $allowedTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);

    return in_array(exif_imagetype($filePath), $allowedTypes);
}

/**
 * Возвращает человекочитаемый размер файла
 * @param $bytes
 * @param int $decimals
 * @return string
 */
function humanFilesize($bytes, $decimals = 2)
{
    $sz = ['b', 'Kb', 'Mb', 'Gb', 'Tb', 'Pb'];
    $factor = floor((strlen($bytes) - 1) / 3);
    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
}

/**
 * Проверяет вышло ли время сессии и если это так пересоздает ее и отправляет пользователя на авторизацию
 * @param int $timeout время жизни сесси в секундах
 */
function startSession($timeout = 1200)
{
    $params = [
        'name' => 'session_id',
        'cookie_lifetime' => $timeout,
        'gc_maxlifetime' => $timeout,
    ];
    session_start($params);
    setcookie(session_name(), session_id(), time() + $timeout, '/');
}

/**
 * Обрабатывает данные от возможной инекции
 * @param mixed $var данные
 * @return mixed
 */
function prepareData($var)
{
    if (is_array($var)) {
        foreach ($var as $k => $v) {
            $var[$k] = prepareData($v);
        }
    } else {
        $var = htmlspecialchars($var);
    }
    return $var;
}

function decodeData($var)
{
    if (is_array($var)) {
        foreach ($var as $k => $v) {
            $var[$k] = decodeData($v);
        }
    } else {
        $var = htmlspecialchars_decode($var);
    }
    return $var;
}

/**
 * Добавляет ошибку в сессию для вывода на странице
 * @param string $error
 */
function setError(string $error)
{
    $_SESSION['errors'][] = $error;
}

/**
 * Вывоит сообщение о ошибке на странице
 */
function printErrors()
{
    if (!empty($_SESSION['errors'])) :?>
        <div class="errors container">
            <ul>
                <?php foreach ($_SESSION['errors'] as $error) : ?>
                    <li><?= $error ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php
        unset($_SESSION['errors']);
    endif;
}

/**
 * Выводит сообщение на страницу
 */
function printMessages()
{
    if (!empty($_SESSION['messages'])) :?>
        <div class="messages container">
            <ul>
                <?php foreach ($_SESSION['messages'] as $message) : ?>
                    <li><?= $message ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php
        unset($_SESSION['messages']);
    endif;
}

/**
 * Добавляет сообщение в сессию для вывода на странице
 * @param string $message
 */
function setMessage(string $message)
{
    $_SESSION['messages'][] = $message;
}

/**
 * Вывод ошибок в дебаг
 */
function errorHandler($level, $message, $file, $line)
{

    switch ($level) {
        case E_ERROR:
            $level = 'Error';
            break;
        case E_WARNING:
            $level = 'Warning';
            break;
        case E_PARSE:
            $level = 'Parse Error';
            break;
        case E_NOTICE:
            $level = 'Notice';
            break;
        case E_DEPRECATED:
            $level = 'Deprecated';
            break;

    }

    debug(compact('level', 'message', 'file', 'line'));
}

/**
 * Выполняет загрузку файлов в указанную директорию
 * @param $files
 * @return mixed
 */
function uploadImages($files, $name = '', $dir = '', $addPrefix = false)
{
    $files = $files['file'];
    $uploaded = 0;
    $errors = [];
    $success = [];

    $pathes = explode(DIRECTORY_SEPARATOR, UPLOAD_PATH . $dir);

    $path = '';
    foreach ($pathes as $item) {
        $path .= $item . DIRECTORY_SEPARATOR;
        if (folderExists($path)) {
            continue;
        } else {
            mkdir($path);
        }
    }

    $imgInFolder = getAllImagesInFolder(mb_strcut($path, strlen(UPLOAD_PATH)));
    $imagesCount = false !== $imgInFolder ? count($imgInFolder) : 0;

    foreach ($files['error'] as $key => $error) {
        if ($key > MAX_UPLOADED_FILES) {
            $errors[] = 'Превышено количество загружаемых файлов.';
            break;
        }
        if ($error != UPLOAD_ERR_OK) {
            $phpFileUploadErrors = array(
                1 => 'Загруженный файл превышает директиву upload_max_filesize в php.ini.',
                2 => 'Загруженный файл превышает размер max_file_size, указанного в HTML-форме.',
                3 => 'Загружаемый файл был загружен лишь частично.',
                4 => 'Файл не был загружен.',
                6 => 'Отсутствует временная папка.',
                7 => 'Не удалось записать файл на диск.',
                8 => 'Расширение PHP остановило загрузку файла.',
            );
            $errors[$files['name'][$key]] = $phpFileUploadErrors[$error];
            continue;
        }
        if (!isImage($files['tmp_name'][$key])) {
            $errors[$files['name'][$key]] = 'Файл не является картинкой.';
            continue;
        }
        if ($files['size'][$key] > MAX_UPLOADED_FILE_SIZE) {
            $errors[$files['name'][$key]] = 'Превышен допустимый размер файла.';
            continue;
        }

        try {
            $tmpName = !empty($name) ? $name . '.' . explode('.', $files['name'][$key])[1] : $files['name'][$key];
            $tmpName = ($addPrefix ? ($imagesCount + $key + 1) . '_' : '') . $tmpName;

            if (move_uploaded_file($files['tmp_name'][$key], $path . DIRECTORY_SEPARATOR . basename($tmpName))) {
                $success[$files['name'][$key]] = 'Файл успешно загружен';
                $response['files'][] = $files['name'][$key];
                $uploaded++;
            }
        } catch (Exception $e) {
            debug($e);
            $errors[$files['name'][$key]] = $e->getMessage();
            continue;
        }
    }

    $tmp = [];
    foreach ($errors as $key => $error) {
        if (is_string($key)) {
            $tmp[] = 'Файл ' . $key . ' ошибка: ' . $error;
        } else {
            $tmp[] = 'Ошибка: ' . $error;
        }
    }
    if ($uploaded != 0) {
        $response['success'] = "Загружено " . declOfNum($uploaded, ['файл', 'файла', 'файлов']) . ' из ' . count($files['name']);
    } else {
        array_unshift($tmp, "Не загружено " . declOfNum(count($files['name']) - $uploaded, ['файл', 'файла', 'файлов']) . ' из ' . count($files['name']));
    }
    $response['errors'] = $tmp;
    return $response;
}

/**
 * Проверяет сущевствование папки и возвращает ее каноничный абсолютный путь
 * @param $path
 * @return bool|string
 */
function folderExists($path)
{
    $path = realpath($path);
    return ($path !== false AND is_dir($path)) ? $path : false;
}

/**
 * Возвращает абсолютный путь от корня сайта к файлу изображения по id элемента если он существует
 * @param $id
 * @param string $path
 * @return bool|string
 */
function getImageById($id, $path = '')
{
    $formats = ['jpg', 'png', 'gif'];

    foreach ($formats as $format) {
        $image = UPLOAD_PATH . $path . DIRECTORY_SEPARATOR . $id . '.' . $format;
        if (file_exists($image)) {
            return '/upload/' . $path . '/' . $id . '.' . $format;
        }
    }
    return false;
}

/**
 * Возвращает список ссылок на картинки по указаному пути
 * @param $path
 * @return array|bool
 */
function getAllImagesInFolder($path)
{

    $dir = UPLOAD_PATH . $path;
    if (is_dir($dir)) {

        $pathes = [];

        foreach (scandir($dir) as $item) {
            if (is_dir($dir . DIRECTORY_SEPARATOR . $item)) {
                continue;
            } elseif (isImage($dir . DIRECTORY_SEPARATOR . $item)) {
                $pathes[] = setWebSleshes('/upload/' . $path . '/' . $item);
            }
        }
        return $pathes;
    }
    return false;

}

/**
 * Проверяет с мобильного ли устройства зашел пользователь
 * @return false|int
 */
function isMobile() {

    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}