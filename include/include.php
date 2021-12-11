<?php
require_once 'constants.php';

if (DEBUG) {
    error_reporting(E_ALL);
} else {
    error_reporting(-1);
}


require_once INCLUDE_PATH . 'functions.php';

set_error_handler('errorHandler', E_ALL);


$routeArray = require_once INCLUDE_PATH . 'route_array.php';

$route = route($routeArray);

require_once INCLUDE_PATH . 'db.php';

startSession();
if (isset($_COOKIE['login'])) {
    setcookie('login', $_COOKIE['login'], time() + 3600 * 24 * 30, '/');
}
