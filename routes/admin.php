<?php

$action = $_GET['action'] ?? '/';
$protected_routes = ['dashboard', 'users-index', 'letters-index'];
if (in_array($action, $protected_routes) && !isset($_SESSION['username'])) {
  $_SESSION['error'] = "Vui lòng đăng nhập!";
  header("Location: " . BASE_URL_ADMIN . "?action=login");
  exit();
}
match ($action) {

  '/'       => (new AuthenController)->index(),
  'login'                 => (new AuthenController)->login(),
  'logout'                => (new AuthenController)->logout(),

  'dashboard'     => (new DashboardController)->index(),
  'users-index'   => (new UserController)->index(),
  'users-create'  => (new UserController)->create(),
  'users-store'   => (new UserController)->store(),
  'users-save'    => (new UserController)->save(),
  'users-edit'    => (new UserController)->edit(),
  'users-update'  => (new UserController)->update(),
  'users-delete'  => (new UserController)->delete(),
  'users-multidelete'         => (new UserController)->multidelete(),

  'letters-index'   => (new LetterController)->index(),
  // 'letters-create'  => (new LetterController)->create(),
  // 'letters-store'   => (new LetterController)->store(),
  // 'letters-save'    => (new LetterController)->save(),


  default         => require_once PATH_VIEW_ADMIN . 'error.php',
};
