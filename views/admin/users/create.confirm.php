<?php
$currentPage = 'Quản lý người dùng';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_ASSETS_ADMIN; ?>header.css">
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_ASSETS_ADMIN; ?>sidebar.css">
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_ASSETS_ADMIN; ?>create.user.confirms.css">
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_ASSETS_ADMIN; ?>popups.css">
  <title>Xác nhận thêm mới người dùng</title>
</head>

<body>
  <?php include PATH_VIEW_ADMIN . 'layout/header.php'; ?>
  <div class="wrapper">
    <?php include PATH_VIEW_ADMIN . 'layout/sidebar.php'; ?>
    <main class="maincontain">
      <div id="container">
        <p>Xác nhận thêm mới người dùng</p>
        <div class="form-create">
          <form id="user-form" method="POST" action="<?php echo BASE_URL_ADMIN; ?>?action=users-save">
            <input type="hidden" name="confirm" value="true">
            <div class="form">
              <label>Tên đăng nhập</label>
              <input type="text" name="username" value="<?php echo htmlspecialchars($_SESSION['userData']['username'] ?? ''); ?>" disabled>
            </div>
            <div class="form">
              <label>Tên người dùng</label>
              <input type="text" name="name" value="<?php echo htmlspecialchars($_SESSION['userData']['name'] ?? ''); ?>" disabled>
            </div>
            <div class="form">
              <label>Mật khẩu</label>
              <input type="password" name="password" value="<?php echo htmlspecialchars($_SESSION['userData']['password'] ?? ''); ?>" disabled>
            </div>
            <div class="form">
              <label>Email</label>
              <input type="text" name="email" value="<?php echo htmlspecialchars($_SESSION['userData']['email'] ?? ''); ?>" disabled>
            </div>
            <div class="form">
              <label>Ngày sinh</label>
              <input type="text" id="datetimepicker" name="birthdate" value="<?php echo htmlspecialchars($_SESSION['userData']['birthdate'] ?? ''); ?>" disabled>
            </div>
            <div class="form">
              <label>Loại người dùng</label>
              <input type="text" name="user_type" value="<?php echo htmlspecialchars($_SESSION['userData']['user_type'] ?? ''); ?>" disabled>
            </div>
            <div class="form">
              <label>Phòng ban</label>
              <input type="text" name="department" value="<?php echo htmlspecialchars($_SESSION['userData']['department'] ?? ''); ?>" disabled>
            </div>
            <div class="form">
              <label>Trạng thái</label>
              <input type="text" name="status" value="<?php echo htmlspecialchars($_SESSION['userData']['status'] ?? ''); ?>" disabled>
            </div>
            <div class="button-action">
              <button type="button" name="confirm" style="background: #007EC6;" onclick="showConfirmDialog(true)">
                Xác nhận lưu
              </button>
              <button type="button" name="back" style="background: #E2005C;" onclick="goBack()">Quay lại</button>
            </div>
          </form>
        </div>
      </div>
    </main>
  </div>

  <?php require_once PATH_VIEW_ADMIN . 'layout/popup.php'; ?>

  <script src="<?php echo BASE_ASSETS_JS; ?>popup.js"></script>
  <script>
    function goBack() {
      window.location.href = '<?php echo BASE_URL_ADMIN; ?>?action=users-create';
    }
  </script>
</body>

</html>