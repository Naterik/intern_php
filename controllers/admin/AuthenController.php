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
      // Lấy dữ liệu từ GET hoặc POST
      $username = $_REQUEST['username'] ?? null; // Sử dụng $_REQUEST để hỗ trợ cả GET và POST
      $password = $_REQUEST['password'] ?? null;

      // Validate dữ liệu ngay lập tức, bất kể GET hay POST
      if (empty($username) || empty($password)) {
        throw new Exception('※Tên đăng nhập hoặc mật khẩu không được để trống!');
      }

      // Chỉ xử lý đăng nhập nếu là POST
      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $userModel = new User();
        $user = $userModel->checkLogin($username, $password);

        if (empty($user)) {
          throw new Exception('※Thông tin tài khoản không đúng!');
        }

        $_SESSION['userId'] = $user['userId'];
        $_SESSION['categoryUser'] = $user['categoryUser'];
        $_SESSION['username'] = $username;
        header("Location: " . BASE_URL_ADMIN . "?action=dashboard");
        exit();
      } else {
        // Nếu là GET và dữ liệu hợp lệ, hiển thị form
        $this->index();
      }
    } catch (Exception $e) {
      $_SESSION['error'] = $e->getMessage();
      error_log("※Login error: " . $e->getMessage());
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
