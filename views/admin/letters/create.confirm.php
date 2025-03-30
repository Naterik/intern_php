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
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_ASSETS_ADMIN; ?>popup.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">
  <title>Quản lý đơn</title>
</head>

<body>
  <?php include PATH_VIEW_ADMIN . 'layout/header.php'; ?>

  <div class="wrapper">
    <?php include PATH_VIEW_ADMIN . 'layout/sidebar.php'; ?>

    <!-- Main content -->
    <main class="maincontain">
      <div id="container">
        <p>Thêm mới đơn</p>
        <div class="form-create">
          <form id="upload-form" enctype="multipart/form-data" method="POST" action="letter.php">
            <div class="form">
              <label>Tiêu đề<span>*</span></label>
              <input type="text" name="username" value="" disabled>
            </div>
            <div class="form">
              <label>Nội dung</label>
              <textarea name="content" disabled></textarea>
            </div>
            <div class="form">
              <label for="user_type">Người duyệt <span>*</span></label>
              <select name="user_type" id="user_type" disabled>
                <option value="" disabled selected>Value</option>
                <option value="admin">Admin</option>
                <option value="user">User</option>
                <option value="guest">Guest</option>
              </select>
            </div>
            <div class="form">
              <label for="department">Loại đơn<span>*</span></label>
              <select name="department" id="department" disabled>
                <option value="" disabled selected>Value</option>
                <option value="it">IT</option>
                <option value="hr">HR</option>
                <option value="marketing">Marketing</option>
              </select>
            </div>
            <div class="form">
              <label>Ngày bắt đầu<span>*</span></label>
              <input type="text" id="datetimepicker" name="datestart" disabled>
            </div>
            <div class="form">
              <label>Ngày kết thúc<span>*</span></label>
              <input type="text" id="datetimepicker2" name="dateend" disabled>
            </div>
            <div class="form file-upload-section">
              <label>Đính kèm<span>*</span></label>
              <label class="custom-file-label" for="file-input">
                <span class="upload-icon"></span>
              </label>
              <input class="custom-file-input" type="file" name="attach" id="file-input" accept="image/*" disabled>
              <div class="file-display" style="display: none;">
                <span class="file-name"></span>
                <button type="button" class="remove-file">Xóa</button>
              </div>
            </div>

            <div class="button-action">
              <button type="button" name="confirm" style="background: #007EC6;" onclick="showConfirmDialog(true)">
                Xác nhận lưu
              </button>
              <button type="button" name="back" style="background: #E2005C;" onclick="goBack()">Quay lại</button>
            </div>
          </form>
          <?php
          if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Xử lý file upload
            if (isset($_FILES['attach']) && $_FILES['attach']['error'] === UPLOAD_ERR_OK) {
              $uploadDir = 'uploads/';
              if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
              }

              $fileName = basename($_FILES['attach']['name']);
              $uploadFile = $uploadDir . $fileName;

              $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
              $fileType = mime_content_type($_FILES['attach']['tmp_name']);
              if (!in_array($fileType, $allowedTypes)) {
                echo '<p style="color: red; margin-left: 120px;">Chỉ cho phép upload file hình ảnh (JPEG, PNG, GIF).</p>';
              } else {
                if (move_uploaded_file($_FILES['attach']['tmp_name'], $uploadFile)) {
                  echo '<p style="color: green; margin-left: 120px;">File ' . htmlspecialchars($fileName) . ' đã được upload thành công!</p>';
                } else {
                  echo '<p style="color: red; margin-left: 120px;">Có lỗi xảy ra khi upload file.</p>';
                }
              }
            } else {
              echo '<p style="color: red; margin-left: 120px;">Có lỗi xảy ra khi gửi thông tin đơn hàng.</p>';
            }
          }
          ?>



        </div>
      </div>
    </main>
  </div>
  <?php require_once PATH_VIEW_ADMIN . 'layout/popup.php'; ?>

  <script src="<?php echo BASE_ASSETS_JS; ?>popup.js"></script>
  <script>
    function goBack() {
      window.location.href = '<?php echo BASE_URL_ADMIN; ?>?action=users&subAction=create';
    }
  </script>
  <script>
    flatpickr("#datetimepicker", {
      dateFormat: "Y-m-d",
      minDate: "1900-01-01",
      maxDate: "2100-12-31",
    });

    flatpickr("#datetimepicker2", {
      dateFormat: "Y-m-d",
      minDate: "1900-01-01",
      maxDate: "2100-12-31",
    });

    document.getElementById('file-input').addEventListener('change', function(event) {
      const fileInput = event.target;
      const fileDisplay = document.querySelector('.file-display');
      const fileNameSpan = document.querySelector('.file-name');
      const fileLabel = document.querySelector('.custom-file-label');

      if (fileInput.files.length > 0) {
        fileNameSpan.textContent = fileInput.files[0].name;
        fileDisplay.style.display = 'flex';
        fileLabel.style.display = 'none';
      } else {
        fileDisplay.style.display = 'none';
        fileLabel.style.display = 'flex';
      }
    });

    document.querySelector('.remove-file').addEventListener('click', function() {
      const fileInput = document.getElementById('file-input');
      const fileDisplay = document.querySelector('.file-display');
      const fileLabel = document.querySelector('.custom-file-label');

      fileInput.value = '';
      fileDisplay.style.display = 'none';
      fileLabel.style.display = 'flex';
    });
  </script>
</body>

</html>