<?php
$currentPage = 'Quản lý đơn';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_ASSETS_ADMIN; ?>header.css">
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_ASSETS_ADMIN; ?>sidebar.css">
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_ASSETS_ADMIN; ?>create.letter.confirm.css">
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_ASSETS_ADMIN; ?>popups.css">
  <title>Xác nhận thêm mới đơn</title>
</head>

<body>
  <?php include PATH_VIEW_ADMIN . 'layout/header.php'; ?>
  <div class="wrapper">
    <?php include PATH_VIEW_ADMIN . 'layout/sidebar.php'; ?>
    <main class="maincontain">
      <div id="container">
        <p>Xác nhận thêm mới đơn</p>
        <div class="form-create">
          <form id="letter-form" method="POST" action="<?php echo BASE_URL_ADMIN; ?>?action=letters-save">
            <input type="hidden" name="confirm" value="true">
            <input type="hidden" name="userId" value="<?php echo htmlspecialchars($_SESSION['letterData']['userId'] ?? $_SESSION['userId'] ?? ''); ?>">
            <div class="form">
              <label>Tiêu đề</label>
              <input type="text" name="title" value="<?php echo htmlspecialchars($_SESSION['letterData']['title'] ?? ''); ?>" disabled>
            </div>
            <div class="form">
              <label>Nội dung</label>
              <textarea name="content" disabled><?php echo htmlspecialchars($_SESSION['letterData']['content'] ?? ''); ?></textarea>
            </div>
            <div class="form">
              <label>Người duyệt</label>
              <input type="text" name="approver" value="<?php echo htmlspecialchars($_SESSION['letterData']['approver'] ?? ''); ?>" disabled>
            </div>
            <div class="form">
              <label>Loại đơn</label>
              <input type="text" name="typesOfApplication" value="<?php echo htmlspecialchars($_SESSION['letterData']['typesOfApplication'] ?? ''); ?>" disabled>
            </div>
            <div class="form">
              <label>Ngày bắt đầu</label>
              <input type="text" name="startDate" value="<?php echo htmlspecialchars($_SESSION['letterData']['startDate'] ?? ''); ?>" disabled>
            </div>
            <div class="form">
              <label>Ngày kết thúc</label>
              <input type="text" name="endDate" value="<?php echo htmlspecialchars($_SESSION['letterData']['endDate'] ?? ''); ?>" disabled>
            </div>
            <div class="form">
              <label>Đính kèm</label>
              <input type="text" name="attachment" value="<?php echo htmlspecialchars($_SESSION['letterData']['attachment'] ?? ''); ?>" disabled>
            </div>
            <div class="button-action">
              <button type="button" name="confirm" style="background: #007EC6;" onclick="showConfirmDialog(true)">Xác nhận lưu</button>
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
      window.location.href = '<?php echo BASE_URL_ADMIN; ?>?action=letters-create';
    }
  </script>
</body>

</html>