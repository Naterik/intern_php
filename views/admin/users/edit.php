<?php
$currentPage = 'Chỉnh sửa người dùng';
if (!is_array($userData)) {
  $_SESSION['error'] = "Không tìm thấy người dùng để chỉnh sửa.";
  header("Location: " . BASE_URL_ADMIN . "?action=users-index");
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_ASSETS_ADMIN; ?>header.css">
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_ASSETS_ADMIN; ?>sidebar.css">
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_ASSETS_ADMIN; ?>create.users.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">
  <title>Chỉnh sửa người dùng</title>

</head>

<body>
  <?php include PATH_VIEW_ADMIN . 'layout/header.php'; ?>
  <div class="wrapper">
    <?php include PATH_VIEW_ADMIN . 'layout/sidebar.php'; ?>
    <main class="maincontain">
      <div id="container">
        <p>Chỉnh sửa người dùng</p>
        <div class="form-create">
          <!-- Lưu ý: Sử dụng method POST và gửi thêm userId qua URL -->
          <form id="user-form" method="POST" action="<?php echo BASE_URL_ADMIN; ?>?action=users-edit&userId=<?php echo htmlspecialchars($userData['userId']); ?>">
            <div class="form">
              <label>Tên đăng nhập<span>*</span></label>
              <input type="text" value="<?php echo htmlspecialchars($inputData['username'] ?? $userData['username']); ?>" disabled>
              <input type="hidden" name="username" value="<?php echo htmlspecialchars($inputData['username'] ?? $userData['username']); ?>">
              <?php if (isset($errors['username'])): ?>
                <span class="error"><?php echo $errors['username']; ?></span>
              <?php endif; ?>
            </div>
            <div class="form">
              <label>Tên người dùng<span>*</span></label>
              <div class="input-form <?php echo isset($errors['name']) ? 'has-error' : ''; ?>">
                <input type="text" name="name" value="<?php echo htmlspecialchars($inputData['name'] ?? $userData['fullname']); ?>">
                <?php if (isset($errors['name'])): ?>
                  <span class="error"><?php echo $errors['name']; ?></span>
                <?php endif; ?>
              </div>
            </div>
            <div class="form">
              <label>Email</label>
              <div class="input-form <?php echo isset($errors['email']) ? 'has-error' : ''; ?>">
                <input type="text" name="email" value="<?php echo htmlspecialchars($inputData['email'] ?? $userData['email']); ?>">
                <?php if (isset($errors['email'])): ?>
                  <span class="error"><?php echo $errors['email']; ?></span>
                <?php endif; ?>
              </div>
            </div>
            <div class="form">
              <label>Ngày sinh</label>
              <div class="input-form">
                <input type="text" id="datetimepicker" name="birthdate" value="<?php echo htmlspecialchars($inputData['birthdate'] ?? $userData['birthDate']); ?>">
              </div>
            </div>
            <div class="form">
              <label>Loại người dùng<span>*</span></label>
              <div class="input-form <?php echo isset($errors['user_type']) ? 'has-error' : ''; ?>">
                <select name="user_type" id="user_type">
                  <option value="Admin" <?php echo ($inputData['user_type'] ?? $userData['categoryUser']) === 'Admin' ? 'selected' : ''; ?>>Admin</option>
                  <option value="User" <?php echo ($inputData['user_type'] ?? $userData['categoryUser']) === 'User' ? 'selected' : ''; ?>>User</option>
                  <option value="Guest" <?php echo ($inputData['user_type'] ?? $userData['categoryUser']) === 'Guest' ? 'selected' : ''; ?>>Guest</option>
                </select>
                <?php if (isset($errors['user_type'])): ?>
                  <span class="error"><?php echo $errors['user_type']; ?></span>
                <?php endif; ?>
              </div>
            </div>
            <div class="form">
              <label>Phòng ban<span>*</span></label>
              <div class="input-form <?php echo isset($errors['department']) ? 'has-error' : ''; ?>">
                <select name="department" id="department">
                  <option value="IT" <?php echo ($inputData['department'] ?? $userData['department']) === 'IT' ? 'selected' : ''; ?>>IT</option>
                  <option value="HR" <?php echo ($inputData['department'] ?? $userData['department']) === 'HR' ? 'selected' : ''; ?>>HR</option>
                  <option value="Marketing" <?php echo ($inputData['department'] ?? $userData['department']) === 'Marketing' ? 'selected' : ''; ?>>Marketing</option>
                </select>
                <?php if (isset($errors['department'])): ?>
                  <span class="error"><?php echo $errors['department']; ?></span>
                <?php endif; ?>
              </div>
            </div>
            <div class="form">
              <label for="status">Trạng thái<span style="color: red;">*</span></label>
              <div class="input-form <?php echo isset($errors['status']) ? 'has-error' : ''; ?>">
                <select name="status" id="status">

                  <option value="Đang hoạt động" <?php echo ($inputData['status'] ?? $userData['status']) === 'Đang hoạt động' ? 'selected' : ''; ?>>Đang hoạt động</option>
                  <option value="Không hoạt động" <?php echo ($inputData['status'] ?? $userData['status']) === 'Không hoạt động' ? 'selected' : ''; ?>>Không hoạt động</option>
                </select>
                <?php if (isset($errors['status'])): ?>
                  <span class="error"><?php echo $errors['status']; ?></span>
                <?php endif; ?>
              </div>
            </div>
            <div class="button-action">
              <button type="submit" name="next" style="background: #007EC6;">Tiếp theo</button>
              <button type="button" name="clear" style="background: #E2005C;" onclick="clearForm()">Xóa trống</button>
            </div>
          </form>
        </div>
      </div>
    </main>
  </div>
  <script>
    window.errors = <?php echo json_encode($errors); ?>;
  </script>
  <script src="<?php echo BASE_ASSETS_JS; ?>user.js"></script>
</body>

</html>