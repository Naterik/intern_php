<?php
$currentPage = 'Quản lý người dùng';
$errors = $errors ?? [];
$inputData = $inputData ?? [];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_ASSETS_ADMIN; ?>header.css">
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_ASSETS_ADMIN; ?>sidebar.css">
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_ASSETS_ADMIN; ?>create.user.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">
  <title>Thêm mới người dùng</title>
</head>

<body>
  <?php include PATH_VIEW_ADMIN . 'layout/header.php'; ?>
  <div class="wrapper">
    <?php include PATH_VIEW_ADMIN . 'layout/sidebar.php'; ?>
    <main class="maincontain">
      <div id="container">
        <p>Thêm mới người dùng</p>
        <div class="form-create">
          <form id="user-form" method="POST" action="<?= BASE_URL_ADMIN ?>?action=users-store">
            <div class="form">
              <label>Tên đăng nhập<span class="required-mark">*</span></label>
              <div class="input-form <?php echo isset($errors['username']) ? 'has-error' : ''; ?>">
                <input type="text" name="username" value="<?php echo htmlspecialchars($inputData['username'] ?? ''); ?>">
                <?php if (isset($errors['username'])): ?>
                  <span class="error"><?php echo $errors['username']; ?></span>
                <?php endif; ?>
              </div>
            </div>
            <div class="form">
              <label>Tên người dùng<span class="required-mark">*</span></label>
              <div class="input-form <?php echo isset($errors['name']) ? 'has-error' : ''; ?>">
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($inputData['name'] ?? ''); ?>">
                <?php if (isset($errors['name'])): ?>
                  <span class="error"><?php echo $errors['name']; ?></span>
                <?php endif; ?>
              </div>
            </div>
            <div class="form">
              <label>Mật khẩu<span class="required-mark">*</span></label>
              <div class="input-form <?php echo isset($errors['password']) ? 'has-error' : ''; ?>">
                <input type="password" id="password" name="password" value="<?php echo htmlspecialchars($inputData['password'] ?? ''); ?>" autocomplete="new-password">
                <?php if (isset($errors['password'])): ?>
                  <span class="error"><?php echo $errors['password']; ?></span>
                <?php endif; ?>
              </div>
            </div>
            <div class="form">
              <label>Email<span class="required-mark">*</span></label>
              <div class="input-form <?php echo isset($errors['email']) ? 'has-error' : ''; ?>">
                <input type="text" id="email" name="email" value="<?php echo htmlspecialchars($inputData['email'] ?? ''); ?>">
                <?php if (isset($errors['email'])): ?>
                  <span class="error"><?php echo $errors['email']; ?></span>
                <?php endif; ?>
              </div>
            </div>
            <div class="form">
              <label>Ngày sinh</label>
              <div class="input-form">
                <input type="text" id="datetimepicker" name="birthdate" value="<?php echo htmlspecialchars($inputData['birthdate'] ?? ''); ?>">
              </div>
            </div>
            <div class="form">
              <label for="user_type">Loại người dùng<span class="required-mark">*</span></label>
              <div class="input-form <?php echo isset($errors['user_type']) ? 'has-error' : ''; ?>">
                <select name="user_type" id="user_type">
                  <option value="" disabled <?php echo empty($inputData['user_type']) ? 'selected' : ''; ?>>Chọn loại</option>
                  <option value="admin" <?php echo ($inputData['user_type'] ?? '') === 'admin' ? 'selected' : ''; ?>>Admin</option>
                  <option value="user" <?php echo ($inputData['user_type'] ?? '') === 'user' ? 'selected' : ''; ?>>User</option>
                  <option value="manager" <?php echo ($inputData['user_type'] ?? '') === 'manager' ? 'selected' : ''; ?>>Manager</option>
                </select>
                <?php if (isset($errors['user_type'])): ?>
                  <span class="error"><?php echo $errors['user_type']; ?></span>
                <?php endif; ?>
              </div>
            </div>
            <div class="form">
              <label for="department">Phòng ban<span class="required-mark">*</span></label>
              <div class="input-form <?php echo isset($errors['department']) ? 'has-error' : ''; ?>">
                <select name="department" id="department">
                  <option value="" disabled <?php echo empty($inputData['department']) ? 'selected' : ''; ?>>Chọn phòng ban</option>
                  <option value="IT" <?php echo ($inputData['department'] ?? '') === 'IT' ? 'selected' : ''; ?>>IT</option>
                  <option value="HR" <?php echo ($inputData['department'] ?? '') === 'HR' ? 'selected' : ''; ?>>HR</option>
                  <option value="Marketing" <?php echo ($inputData['department'] ?? '') === 'Marketing' ? 'selected' : ''; ?>>Marketing</option>
                </select>
                <?php if (isset($errors['department'])): ?>
                  <span class="error"><?php echo $errors['department']; ?></span>
                <?php endif; ?>
              </div>
            </div>
            <div class="form">
              <label for="status">Trạng thái<span class="required-mark">*</span></label>
              <div class="input-form <?php echo isset($errors['status']) ? 'has-error' : ''; ?>">
                <select name="status" id="status">
                  <option value="" disabled <?php echo empty($inputData['status']) ? 'selected' : ''; ?>>Chọn trạng thái</option>
                  <option value="Đang hoạt động" <?php echo ($inputData['status'] ?? '') === 'Đang hoạt động' ? 'selected' : ''; ?>>Đang hoạt động</option>
                  <option value="Không hoạt động" <?php echo ($inputData['status'] ?? '') === 'Không hoạt động' ? 'selected' : ''; ?>>Không hoạt động</option>
                </select>
                <?php if (isset($errors['status'])): ?>
                  <span class="error"><?php echo $errors['status']; ?></span>
                <?php endif; ?>
              </div>
            </div>
            <div class="button-action">
              <button type="submit" name="next" style="background: #007EC6;">Tiếp theo</button>
              <button type="button" name="clear" style="background: #E2005C;">Xóa trống</button>
            </div>
          </form>
        </div>
      </div>
    </main>
  </div>
  <script src="<?php echo BASE_ASSETS_JS; ?>user.js"></script>
</body>

</html>