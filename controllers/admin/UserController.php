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
    $userData = $this->userModel->getUserById($userId);
    if (!$userData) {
      $_SESSION['error'] = "Không tìm thấy người dùng.";
      header("Location: " . BASE_URL_ADMIN . "?action=users-index");
      exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $inputData = $_POST;
      $errors = $this->validateUserData($inputData, true, $userData);

      if (!empty($errors)) {
        require_once PATH_VIEW_ADMIN . 'users/edit.php';
      } else {
        // Lưu dữ liệu vào session, bao gồm cả mật khẩu hiện tại
        $_SESSION['editUserData'] = $inputData;
        $_SESSION['editUserData']['userId'] = $userId;
        if (ob_get_length()) ob_clean();
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
          throw new Exception("Dữ liệu session không tồn tại.");
        }
        $editData = $_SESSION['editUserData'];
        $userId = (int)$editData['userId'];

        // Lấy dữ liệu hiện tại từ cơ sở dữ liệu
        $currentUser = $this->userModel->getUserById($userId);
        if (!$currentUser) {
          throw new Exception("Không tìm thấy người dùng trong cơ sở dữ liệu.");
        }

        // Chuẩn bị dữ liệu mới
        $data = [
          'username'      => $editData['username'],
          'fullname'      => $editData['name'],
          'email'         => $editData['email'],
          'birthDate'     => !empty($editData['birthdate']) ? $editData['birthdate'] : null,
          'categoryUser'  => $editData['user_type'],
          'department'    => $editData['department'],
          'status'        => $editData['status'],
          'password'      => ($editData['password'] !== $currentUser['password']) ? hashPassword($editData['password']) : $currentUser['password']
        ];

        // So sánh dữ liệu mới với dữ liệu cũ
        $hasChanges = false;
        foreach ($data as $key => $value) {
          if ($value !== $currentUser[$key]) {
            $hasChanges = true;
            break;
          }
        }

        // Chỉ thực thi UPDATE nếu có thay đổi
        if ($hasChanges) {
          $this->userModel->update($data, "userId = $userId");
        }

        unset($_SESSION['editUserData']);
        $_SESSION['success'] = "Cập nhật thành công!";
        header("Location: " . BASE_URL_ADMIN . "?action=users-index");
        exit();
      } catch (Exception $e) {
        $_SESSION['error'] = "Lỗi hệ thống: " . $e->getMessage();
        header("Location: " . BASE_URL_ADMIN . "?action=users-edit&userId=" . ($userId ?? 0));
        exit();
      }
    } else {
      if (empty($_SESSION['editUserData'])) {
        $_SESSION['error'] = "Dữ liệu không hợp lệ.";
        header("Location: " . BASE_URL_ADMIN . "?action=users-index");
        exit();
      }
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

  private function validateUserData($data, $isEdit = false, $originalData = [])
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

    // Kiểm tra Họ và tên
    if (empty($data['name'])) {
      $errors['name'] = "※Tên người dùng là bắt buộc.";
    }

    // Kiểm tra mật khẩu
    if ($isEdit && !empty($originalData)) {
      // Nếu mật khẩu không thay đổi (giữ nguyên giá trị cũ), bỏ qua validate
      if (isset($data['password']) && $data['password'] === $originalData['password']) {
        // Không làm gì, bỏ qua validate
      } else {
        // Nếu mật khẩu thay đổi
        if (empty($data['password'])) {
          $errors['password'] = "※Mật khẩu không được để trống khi thay đổi.";
        } elseif (!preg_match('/^(?=.*[a-z])(?=.*\d)(?=.*[^\w]).{8,}$/', $data['password'])) {
          $errors['password'] = "※Mật khẩu phải có ít nhất 8 ký tự, bao gồm chữ thường, số và ký tự đặc biệt.";
        }
      }
    } elseif (!$isEdit) {
      // Trường hợp tạo mới, luôn validate mật khẩu
      if (empty($data['password'])) {
        $errors['password'] = "※Mật khẩu là bắt buộc.";
      } elseif (!preg_match('/^(?=.*[a-z])(?=.*\d)(?=.*[^\w]).{8,}$/', $data['password'])) {
        $errors['password'] = "※Mật khẩu phải có ít nhất 8 ký tự, bao gồm chữ thường, số và ký tự đặc biệt.";
      }
    }

    // Kiểm tra Email
    if (empty($data['email'])) {
      $errors['email'] = "※Email là bắt buộc.";
    } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
      $errors['email'] = "※Email không hợp lệ.";
    }

    // Kiểm tra Loại người dùng
    if (empty($data['user_type'])) {
      $errors['user_type'] = "※Loại người dùng là bắt buộc.";
    }

    // Kiểm tra Phòng ban
    if (empty($data['department'])) {
      $errors['department'] = "※Phòng ban là bắt buộc.";
    }

    // Kiểm tra Trạng thái
    if (empty($data['status'])) {
      $errors['status'] = "※Trạng thái là bắt buộc.";
    }

    return $errors;
  }
}
