<?php
$currentPage = 'Quản lý đơn';
$letter = $this->letterModel->getLetterById($_GET['letterId']);
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
  <title>Duyệt đơn</title>
</head>

<body>
  <?php include PATH_VIEW_ADMIN . 'layout/header.php'; ?>
  <div class="wrapper">
    <?php include PATH_VIEW_ADMIN . 'layout/sidebar.php'; ?>
    <main class="maincontain">
      <div id="container">
        <p>Duyệt đơn (ID: <?php echo htmlspecialchars($letter['letterId']); ?>)</p>
        <div class="form-create">
          <form id="approve-form" method="POST" action="<?php echo BASE_URL_ADMIN; ?>?action=letters-approve&letterId=<?php echo $letter['letterId']; ?>">
            <input type="hidden" name="letterId" value="<?php echo htmlspecialchars($letter['letterId']); ?>">
            <input type="hidden" name="status" id="form-status">
            <input type="hidden" name="reason" id="form-reason">
            <div class="form">
              <label>Tiêu đề</label>
              <input type="text" name="title" value="<?php echo htmlspecialchars($letter['title'] ?? ''); ?>" disabled>
            </div>
            <div class="form">
              <label>Nội dung</label>
              <textarea name="content" disabled><?php echo htmlspecialchars($letter['content'] ?? ''); ?></textarea>
            </div>
            <div class="form">
              <label>Người duyệt</label>
              <input type="text" name="approver" value="<?php echo htmlspecialchars($letter['approver'] ?? ''); ?>" disabled>
            </div>
            <div class="form">
              <label>Loại đơn</label>
              <input type="text" name="typesOfApplication" value="<?php echo htmlspecialchars($letter['typesOfApplication'] ?? ''); ?>" disabled>
            </div>
            <div class="form">
              <label>Ngày bắt đầu</label>
              <input type="text" name="startDate" value="<?php echo htmlspecialchars($letter['startDate'] ?? ''); ?>" disabled>
            </div>
            <div class="form">
              <label>Ngày kết thúc</label>
              <input type="text" name="endDate" value="<?php echo htmlspecialchars($letter['endDate'] ?? ''); ?>" disabled>
            </div>
            <div class="form">
              <label>Đính kèm</label>
              <input type="text" name="attachment" value="<?php echo htmlspecialchars($letter['attachment'] ?? ''); ?>" disabled>
            </div>
            <div class="form">
              <label>Trạng thái</label>
              <input type="text" name="status_display" value="<?php echo htmlspecialchars($letter['status'] ?? ''); ?>" disabled>
            </div>
            <?php if ($letter['status'] === 'đơn mới'): ?>
              <div class="button-action">
                <button type="button" onclick="showConfirmDialog(true)" style="background: #007EC6;">Duyệt đơn</button>
                <button type="button" onclick="showCancelDialog(true)" style="background: #E2005C;">Hủy đơn</button>
              </div>
            <?php endif; ?>
          </form>
        </div>
        <?php if (isset($_SESSION['success'])): ?>
          <p style="color: green;"><?php echo htmlspecialchars($_SESSION['success']);
                                    unset($_SESSION['success']); ?></p>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
          <p style="color: red;"><?php echo htmlspecialchars($_SESSION['error']);
                                  unset($_SESSION['error']); ?></p>
        <?php endif; ?>
      </div>
    </main>
  </div>

  <!-- Popup xác nhận duyệt đơn -->
  <dialog id="confirm-dialog">
    <div class="dialog-wrapper">
      <div class="dialog-header">
        <span>Thông báo</span>
        <img src="<?php echo BASE_ASSETS_ADMIN; ?>img/material-symbols_close-rounded.png" onclick="showConfirmDialog(false)" alt="Close" />
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
        <img src="<?php echo BASE_ASSETS_ADMIN; ?>img/material-symbols_close-rounded.png" onclick="showCancelDialog(false)" alt="Close" />
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
        }
        reasonInput.value = reason;
        showCancelDialog(false);
      } else {
        showConfirmDialog(false);
      }
      form.submit();
    }
  </script>
</body>

</html>