<?php


require_once PATH_MODEL . 'User.php';

class AuthenController
{
  public function index()
  {
    $error = $_SESSION['error'] ?? null;
    require_once PATH_VIEW_ADMIN . 'login.php';
  }

  public function login()
  {
    try {
      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'] ?? null;
        $password = $_POST['password'] ?? null;

        if (empty($username) || empty($password)) {
          throw new Exception('※Tên đăng nhập hoặc mật khẩu không được để trống!');
        }
        $userModel = new User();
        $user = $userModel->checkLogin($username, hashPassword($password));
        if (empty($user)) {
          throw new Exception('※Thông tin tài khoản hoặc mật khẩu không đúng!');
        }
        $_SESSION['userId'] = $user['userId'];
        $_SESSION['categoryUser'] = $user['categoryUser'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['username'] = $username;

        header("Location: " . BASE_URL_ADMIN . "?action=dashboard");
        exit();
      } else {
        echo
        $this->index();
      }
    } catch (Exception $e) {
      $_SESSION['error'] = $e->getMessage();
      $this->index();
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
