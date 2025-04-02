<?php

class UserController
{
  private $userModel;

  public function __construct()
  {
    $this->userModel = new User();
  }

  public function index()
  {
    $searchTerm = $_GET['search'] ?? '';
    $page = (int)($_GET['page'] ?? 1);
    $limit = 10;
    $offset = ($page - 1) * $limit;
    $allUsers = $this->userModel->getAllUsers();
    if ($searchTerm) {
      $filteredUsers = array_filter($allUsers, function ($user) use ($searchTerm) {
        return stripos($user['userId'], $searchTerm) !== false ||
          stripos($user['username'], $searchTerm) !== false;
      });
    } else {
      $filteredUsers = $allUsers;
    }
    $totalUsers = count($filteredUsers);
    $totalPages = ceil($totalUsers / $limit);
    $users = array_slice($filteredUsers, $offset, $limit);
    $pagination = ($totalUsers > $limit);

    require_once PATH_VIEW_ADMIN . 'users/index.php';
  }

  public function create()
  {
    $inputData = $_SESSION['userData'] ?? [];
    $errors = [];
    require_once PATH_VIEW_ADMIN . 'users/create.php';
  }

  public function store()
  {
    $inputData = $_POST;
    $errors = $this->validateUserData($inputData);

    if (!empty($errors)) {
      require_once PATH_VIEW_ADMIN . 'users/create.php';
    } else {
      $_SESSION['userData'] = $inputData;
      require_once PATH_VIEW_ADMIN . 'users/create.confirm.php';
    }
  }

  public function save()
  {
    if (isset($_POST['confirm']) && $_POST['confirm'] === 'true') {
      if (isset($_SESSION['userData'])) {
        $userData = $_SESSION['userData'];
        $data = [
          'username'     => $userData['username'],
          'fullname'     => $userData['name'],
          'password'     => hashPassword($userData['password']),
          'email'        => $userData['email'],
          'birthDate'    => $userData['birthdate'] ?? null,
          'categoryUser' => $userData['user_type'],
          'department'   => $userData['department'],
          'status'       => $userData['status']
        ];

        try {
          $this->userModel->create($data);

          unset($_SESSION['userData']);
          $_SESSION['success'] = "Người dùng đã được tạo thành công.";
          header("Location: " . BASE_URL_ADMIN . "?action=users-index");
          exit();
        } catch (Exception $e) {
          error_log($e->getMessage());
          $_SESSION['error'] = "Lỗi hệ thống, vui lòng thử lại sau.";
          header("Location: " . BASE_URL_ADMIN . "?action=users-create");
          exit();
        }
      } else {
        $_SESSION['error'] = "Dữ liệu không hợp lệ.";
        header("Location: " . BASE_URL_ADMIN . "?action=users-create");
        exit();
      }
    }
  }

  public function edit()
  {

    $userId = $_GET['userId'] ?? null;
    if (!$userId) {
      $_SESSION['error'] = "Không tìm thấy ID người dùng.";
      header("Location: " . BASE_URL_ADMIN . "?action=users-index");
      exit();
    }

    $userData = $this->userModel->getUserById($userId);
    if (!$userData) {
      $_SESSION['error'] = "Không tìm thấy người dùng.";
      header("Location: " . BASE_URL_ADMIN . "?action=users-index");
      exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $inputData = $_POST;
      $errors = $this->validateUserData($inputData, true);

      if (!empty($errors)) {

        require_once PATH_VIEW_ADMIN . 'users/edit.php';
      } else {

        $_SESSION['editUserData'] = $inputData;
        $_SESSION['editUserData']['userId'] = $userId;
        header("Location: " . BASE_URL_ADMIN . "?action=users-update");
        exit();
      }
    } else {
      $inputData = $userData;
      require_once PATH_VIEW_ADMIN . 'users/edit.php';
    }
  }

  public function update()
  {
    if (isset($_POST['confirm']) && $_POST['confirm'] === 'true') {
      try {
        if (empty($_SESSION['editUserData'])) {
          throw new Exception("Invalid session data");
        }

        $editData = $_SESSION['editUserData'];
        $userId = (int)$editData['userId'];
        $data = [
          'username'      => $editData['username'],
          'password'      =>  hashPassword($editData['password']),
          'fullname'      => $editData['name'],
          'email'         => $editData['email'],
          'birthDate'     => !empty($editData['birthdate']) ? $editData['birthdate'] : null,
          'categoryUser'  => $editData['user_type'],
          'department'    => $editData['department'],
          'status'        => $editData['status']
        ];

        // Thực hiện update với transaction
        $this->userModel->update($data, "userId = $userId");

        unset($_SESSION['editUserData']);
        $_SESSION['success'] = "Cập nhật thành công!";
        header("Location: " . BASE_URL_ADMIN . "?action=users-index");
        exit();
      } catch (Exception $e) {
        error_log("Lỗi cập nhật: " . $e->getMessage());
        $_SESSION['error'] = "Lỗi hệ thống: " . $e->getMessage();
        header("Location: " . BASE_URL_ADMIN . "?action=users-edit&userId=" . ($userId ?? 0));
        exit();
      }
    } else {
      require_once PATH_VIEW_ADMIN . 'users/edit.confirm.php';
    }
  }

  public function delete()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $userId = $_POST['userId'] ?? '';
      if (empty($userId) || !is_numeric($userId)) {
        $_SESSION['error'] = "※Không tìm thấy ID người dùng.";
        header("Location: " . BASE_URL_ADMIN . "?action=users-index");
        exit();
      }

      if ($this->userModel->deleteByUserId((int)$userId)) {
        $_SESSION['success'] = "Người dùng đã được xóa thành công.";
      } else {
        $_SESSION['error'] = "※Không thể xóa người dùng.";
      }
      header("Location: " . BASE_URL_ADMIN . "?action=users-index");
      exit();
    } else {
      header("Location: " . BASE_URL_ADMIN . "?action=users-index");
      exit();
    }
  }

  public function multidelete()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $userIdsJSON = $_POST['userIds'] ?? '[]';
      $userIds = json_decode($userIdsJSON, true);

      if (!empty($userIds)) {
        if ($this->userModel->deleteMultipleUsers($userIds)) {
          $_SESSION['success'] = "Đã xóa các người dùng được chọn.";
        } else {
          $_SESSION['error'] = "※Không thể xóa người dùng nào.";
        }
      } else {
        $_SESSION['error'] = "Không có người dùng nào được chọn.";
      }
      header("Location: " . BASE_URL_ADMIN . "?action=users-index");
      exit();
    }
  }

  private function validateUserData($data, $isEdit = false)
  {
    $errors = [];

    if (!$isEdit) {
      if (empty($data['username'])) {
        $errors['username'] = "※Tên đăng nhập là bắt buộc.";
      } else {
        if ($this->userModel->checkUsernameExists($data['username'])) {
          $errors['username'] = "※Tên đăng nhập đã tồn tại.";
        }
      }
    }

    if (empty($data['name'])) {
      $errors['name'] = "※Tên người dùng là bắt buộc.";
    }
    if (empty($data['password'])) {
      $errors['password'] = "※Mật khẩu là bắt buộc.";
    } elseif (!preg_match('/^(?=.*[a-z])(?=.*\d)(?=.*[^\w]).{8,}$/', $data['password'])) {
      $errors['password'] = "※Mật khẩu phải có ít nhất 8 ký tự, bao gồm chữ thường, số và ký tự đặc biệt.";
    }

    if (empty($data['email'])) {
      $errors['email'] = "※Email là bắt buộc.";
    } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
      $errors['email'] = "※Email không hợp lệ.";
    }

    if (empty($data['user_type'])) {
      $errors['user_type'] = "※Loại người dùng là bắt buộc.";
    }

    if (empty($data['department'])) {
      $errors['department'] = "※Phòng ban là bắt buộc.";
    }

    if (empty($data['status'])) {
      $errors['status'] = "※Trạng thái là bắt buộc.";
    }

    return $errors;
  }
}
