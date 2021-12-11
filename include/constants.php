<?php
define('DEBUG', false);
define('ROOT', str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR));
define('INCLUDE_PATH', ROOT . 'include' . DIRECTORY_SEPARATOR);
define('UPLOAD_PATH', ROOT . 'upload' . DIRECTORY_SEPARATOR);

define('MAX_UPLOADED_FILES', 4);
define('MAX_UPLOADED_FILE_SIZE', 5242880);
define('MAX_RESULTS_PER_PAGE', 9);


