<?php
$currentPage = 'Xác nhận chỉnh sửa người dùng';
?>

<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_ASSETS_ADMIN; ?>header.css">
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_ASSETS_ADMIN; ?>sidebar.css">
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_ASSETS_ADMIN; ?>edit.user.confirm.css">
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_ASSETS_ADMIN; ?>popups.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <title>Xác nhận chỉnh sửa người dùng</title>
</head>

<body>
  <?php include PATH_VIEW_ADMIN . 'layout/header.php'; ?>
  <div class="wrapper">
    <?php include PATH_VIEW_ADMIN . 'layout/sidebar.php'; ?>
    <main class="maincontain">
      <div id="container">
        <p>Xác nhận chỉnh sửa người dùng</p>
        <div class="form-create">
          <form id="user-form" method="POST" action="<?php echo BASE_URL_ADMIN; ?>?action=users-update">
            <input type="hidden" name="confirm" value="true">
            <input type="hidden" name="username" value="<?php echo htmlspecialchars($_SESSION['editUserData']['username'] ?? ''); ?>">
            <input type="hidden" name="userId" value="<?php echo htmlspecialchars($_SESSION['editUserData']['userId'] ?? ''); ?>">
            <div class="form">
              <label>Tên đăng nhập</label>
              <input type="text" value="<?php echo htmlspecialchars($_SESSION['editUserData']['username'] ?? ''); ?>" disabled>
            </div>
            <div class="form">
              <label>Tên người dùng<span>*</span></label>
              <input type="text" name="name" value="<?php echo htmlspecialchars($_SESSION['editUserData']['name'] ?? ''); ?>" disabled>
            </div>
            <div class="form">
              <label>Email</label>
              <input type="text" name="email" value="<?php echo htmlspecialchars($_SESSION['editUserData']['email'] ?? ''); ?>" disabled>
            </div>
            <div class="form">
              <label>Mật khẩu</label>
              <input type="password" name="password" value="<?php echo htmlspecialchars(($editData['password'] ?? $currentUser['password']) !== $currentUser['password'] ? $editData['password'] : 'Không thay đổi'); ?>" disabled>
            </div>
            <div class="form">
              <label>Ngày sinh</label>
              <input type="text" id="datetimepicker" name="birthdate" value="<?php echo htmlspecialchars($_SESSION['editUserData']['birthdate'] ?? ''); ?>" disabled>
            </div>
            <div class="form">
              <label>Loại người dùng<span>*</span></label>
              <input type="text" name="user_type" value="<?php echo htmlspecialchars($_SESSION['editUserData']['user_type'] ?? ''); ?>" disabled>
            </div>
            <div class="form">
              <label>Phòng ban<span>*</span></label>
              <input type="text" name="department" value="<?php echo htmlspecialchars($_SESSION['editUserData']['department'] ?? ''); ?>" disabled>
            </div>
            <div class="form">
              <label>Trạng thái</label>
              <input type="text" name="status" value="<?php echo htmlspecialchars($_SESSION['editUserData']['status'] ?? ''); ?>" disabled>
            </div>
            <div class="button-action">
              <button type="button" style="background: #007EC6;" onclick="showConfirmDialog(true)">Lưu lại</button>
              <button type="button" style="background: #E2005C;" onclick="goBack()">Quay lại</button>
            </div>
          </form>
        </div>
      </div>
    </main>
  </div>
  <?php include PATH_VIEW_ADMIN . 'layout/popup.php'; ?>
  <script src="<?php echo BASE_ASSETS_JS; ?>popup.js"></script>
  <script>
    function goBack() {
      window.location.href = '<?php echo BASE_URL_ADMIN; ?>?action=users-edit&userId=<?php echo htmlspecialchars($_SESSION['editUserData']['userId'] ?? ''); ?>';
    }
  </script>
</body>

</html>