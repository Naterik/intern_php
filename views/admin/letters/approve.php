<?php
$currentPage = 'Quản lý đơn';

// Lấy thông tin đơn từ controller
if (!isset($letter)) {
  $_SESSION['error'] = "Không tìm thấy thông tin đơn.";
  header("Location: " . BASE_URL_ADMIN . "?action=letters-index");
  exit();
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_ASSETS_ADMIN; ?>header.css">
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_ASSETS_ADMIN; ?>sidebar.css">
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_ASSETS_ADMIN; ?>create.letter.confirm.css">
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_ASSETS_ADMIN; ?>popups.css">
  <title>Xác nhận duyệt đơn</title>
</head>

<body>
  <?php include PATH_VIEW_ADMIN . 'layout/header.php'; ?>
  <div class="wrapper">
    <?php include PATH_VIEW_ADMIN . 'layout/sidebar.php'; ?>
    <main class="maincontain">
      <div id="container">
        <p>Xác nhận duyệt đơn (ID: <?php echo htmlspecialchars($letter['letterId']); ?>)</p>
        <div class="form-create">
          <form id="approve-form" method="POST" action="<?php echo BASE_URL_ADMIN; ?>?action=letters-approve&letterId=<?php echo htmlspecialchars($letter['letterId']); ?>">
            <input type="hidden" name="letterId" value="<?php echo htmlspecialchars($letter['letterId']); ?>">
            <input type="hidden" name="status" id="form-status">
            <input type="hidden" name="reason" id="form-reason">
            <div class="form">
              <label>Tiêu đề</label>
              <div class="input-form">
                <input type="text" name="title" value="<?php echo htmlspecialchars($letter['title'] ?? ''); ?>" disabled>
              </div>
            </div>
            <div class="form">
              <label>Nội dung</label>
              <div class="input-form">
                <textarea name="content" disabled><?php echo htmlspecialchars($letter['content'] ?? ''); ?></textarea>
              </div>
            </div>
            <div class="form">
              <label>Người duyệt</label>
              <div class="input-form">
                <select name="approver" disabled>
                  <option value="<?php echo htmlspecialchars($letter['approver'] ?? ''); ?>" selected>
                    <?php echo htmlspecialchars($letter['approver'] ?? 'Value'); ?>
                  </option>
                </select>
              </div>
            </div>
            <div class="form">
              <label>Loại đơn</label>
              <div class="input-form">
                <select name="typesOfApplication" disabled>
                  <option value="<?php echo htmlspecialchars($letter['typesOfApplication'] ?? ''); ?>" selected>
                    <?php echo htmlspecialchars($letter['typesOfApplication'] ?? 'Value'); ?>
                  </option>
                </select>
              </div>
            </div>
            <div class="form">
              <label>Ngày bắt đầu</label>
              <div class="input-form">
                <input type="text" id="datetimepicker" name="startDate" value="<?php echo htmlspecialchars($letter['startDate'] ?? ''); ?>" disabled>
              </div>
            </div>
            <div class="form">
              <label>Ngày kết thúc</label>
              <div class="input-form">
                <input type="text" id="datetimepicker2" name="endDate" value="<?php echo htmlspecialchars($letter['endDate'] ?? ''); ?>" disabled>
              </div>
            </div>
            <div class="form file-upload-section">
              <label>Đính kèm</label>
              <div class="input-form">
                <?php if (empty($letter['attachment'])): ?>
                  <label class="custom-file-label" for="file-input">
                    <span class="upload-icon"></span>
                  </label>
                  <input class="custom-file-input" type="file" name="attach" id="file-input" accept="image/*" disabled>
                <?php endif; ?>
                <?php if (!empty($letter['attachment'])): ?>
                  <div class="file-display" style="display: block;">
                    <span class="file-name"><?php echo htmlspecialchars(basename($letter['attachment'])); ?></span>
                  </div>
                <?php endif; ?>
              </div>
            </div>
            <div class="button-action">
              <?php if ($letter['status'] === 'đơn mới'): ?>
                <button type="button" onclick="showConfirmDialog(true)" style="background: #007EC6;">Xác nhận duyệt</button>
                <button type="button" onclick="showCancelDialog(true)" style="background: #E2005C;">Hủy đơn</button>
              <?php endif; ?>
            </div>
          </form>
        </div>
      </div>
    </main>
  </div>

  <!-- Popup xác nhận duyệt đơn -->
  <dialog id="confirm-dialog">
    <div class="dialog-wrapper">
      <div class="dialog-header">
        <span>Thông báo</span>
        <img src="<?php echo BASE_ASSETS_UPLOAD; ?>img/material-symbols_close-rounded.png" onclick="showConfirmDialog(false)" alt="Close" />
      </div>
      <form id="confirm-form">
        <label>Bạn có chắc chắn muốn duyệt đơn này?</label>
        <div class="modal-buttons">
          <button type="button" onclick="submitForm('đã duyệt')">OK</button>
          <button type="button" onclick="showConfirmDialog(false)">Cancel</button>
        </div>
      </form>
    </div>
  </dialog>

  <!-- Dialog hủy đơn -->
  <dialog id="cancel-dialog">
    <div class="dialog-wrapper">
      <div class="dialog-header">
        <span>Thông báo</span>
        <img src="<?php echo BASE_ASSETS_UPLOAD; ?>img/material-symbols_close-rounded.png" onclick="showCancelDialog(false)" alt="Close" />
      </div>
      <form id="cancel-form">
        <label>Lý do hủy đơn <span style="color:red">*</span></label>
        <input type="text" name="reason" id="cancel-reason" required>
        <div class="modal-buttons">
          <button type="button" onclick="submitForm('đã hủy')" class="modal-buttons-ok">OK</button>
        </div>
      </form>
    </div>
  </dialog>

  <script src="<?php echo BASE_ASSETS_JS; ?>popup.js"></script>
  <script>
    const confirmDialog = document.getElementById('confirm-dialog');
    const cancelDialog = document.getElementById('cancel-dialog');

    function showConfirmDialog(show) {
      if (show) confirmDialog.showModal();
      else confirmDialog.close();
    }

    function showCancelDialog(show) {
      if (show) cancelDialog.showModal();
      else cancelDialog.close();
    }

    function submitForm(status) {
      const form = document.getElementById('approve-form');
      const statusInput = document.getElementById('form-status');
      const reasonInput = document.getElementById('form-reason');
      statusInput.value = status;
      if (status === 'đã hủy') {
        const reason = document.getElementById('cancel-reason').value;
        if (!reason.trim()) {
          alert('Lý do hủy đơn không được để trống!');
          return;
          Q
        }
        reasonInput.value = reason;
        showCancelDialog(false);
      } else {
        showConfirmDialog(false);
      }
      form.submit();
    }

    // Thêm script để hiển thị alert nếu có lỗi
    <?php if (isset($errorToDisplay)): ?>
      alert('<?php echo addslashes($errorToDisplay); ?>');
      window.location.href = '<?php echo BASE_URL_ADMIN; ?>?action=letters-index';
    <?php endif; ?>
  </script>
</body>

</html>