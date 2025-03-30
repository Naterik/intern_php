<?php

require_once PATH_MODEL . 'User.php';

class AuthenController
{
  public function index()
  {
    $error = $_SESSION['error'] ?? null;
    unset($_SESSION['error']);
    require_once PATH_VIEW_ADMIN . 'login.php';
  }

  public function login()
  {
    try {
      if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        throw new Exception('※Yêu cầu phương thức phải là POST');
      }

      $username = $_POST['username'] ?? null;
      $password = $_POST['password'] ?? null;

      if (empty($username) || empty($password)) {
        throw new Exception('※Tên đăng nhập hoặc mật khẩu không được để trống!');
      }

      $userModel = new User();
      $user = $userModel->checkLogin($username, $password);

      if (empty($user)) {
        throw new Exception('※Thông tin tài khoản không đúng!');
      }

      $_SESSION['username'] = $username;
      header("Location: " . BASE_URL_ADMIN . "?action=dashboard");
      exit();
    } catch (Exception $e) {
      $_SESSION['success'] = false;
      $_SESSION['msg'] = $e->getMessage();
      error_log("※Login error: " . $e->getMessage());
      header('Location: ' . BASE_URL);
      exit();
    }
  }

  public function logout()
  {
    session_destroy();
    header("Location: " . BASE_URL);
    exit();
  }
}
