<?php
// configs/env.php
define('BASE_URL', 'http://localhost/figma/');
define('BASE_URL_ADMIN', BASE_URL . 'admin/');
define('PATH_ROOT', __DIR__ . '/../');
define('PATH_VIEW_ADMIN', PATH_ROOT . 'views/admin/');
define('BASE_ASSETS_ADMIN', BASE_URL . 'assets/css/');
define('BASE_ASSETS_JS', BASE_URL . 'assets/js/');
define('BASE_ASSETS_UPLOAD', BASE_URL . 'assets/uploads/');
define('PATH_ASSETS_UPLOADS', PATH_ROOT . 'assets/uploads/');
define('PATH_CONTROLLER_ADMIN', PATH_ROOT . 'controllers/admin/');
define('PATH_MODEL', PATH_ROOT . 'models/');

define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'sanshin');
define('DB_OPTIONS', [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);
