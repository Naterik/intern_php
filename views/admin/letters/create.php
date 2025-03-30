<?php
$currentPage = 'Quản lý đơn';
$errors = [];
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['next'])) {
  if (empty(trim($_POST['username']))) $errors['username'] = '※Tên người dùng không được để trống';
  if (empty($_POST['user_type'])) $errors['user_type'] = '※Vui lòng chọn người duyệt';
  if (empty($_POST['department'])) $errors['department'] = '※Vui lòng chọn loại đơn';
  if (empty($_POST['datestart'])) $errors['datestart'] = '※Ngày bắt đầu không được để trống';
  if (empty($_POST['dateend'])) $errors['dateend'] = '※Ngày kết thúc không được để trống';

  if (isset($_FILES['attach']) && $_FILES['attach']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = 'uploads/';
    if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);
    $fileName = basename($_FILES['attach']['name']);
    $uploadFile = $uploadDir . $fileName;
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $fileType = mime_content_type($_FILES['attach']['tmp_name']);

    if (!in_array($fileType, $allowedTypes)) {
      $errors['attach'] = '※Chỉ cho phép upload file hình ảnh (JPEG, PNG, GIF)';
    } else {
      if (move_uploaded_file($_FILES['attach']['tmp_name'], $uploadFile)) {
        $success_message = 'File ' . htmlspecialchars($fileName) . ' đã được upload thành công!';
      } else {
        $errors['attach'] = '※Có lỗi xảy ra khi upload file';
      }
    }
  } else {
    $errors['attach'] = '※Vui lòng chọn một file để upload';
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_ASSETS_ADMIN; ?>header.css">
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_ASSETS_ADMIN; ?>sidebar.css">
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_ASSETS_ADMIN; ?>create.letters
  .css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">
  <title>Quản lý đơn</title>
  <style>
    .error-message {
      color: red;
      font-size: 14px;
      margin-top: 5px;
      display: block;
    }

    .form {
      margin-bottom: 15px;
    }
  </style>
</head>

<body>
  <?php include PATH_VIEW_ADMIN . 'layout/header.php'; ?>

  <div class="wrapper">
    <?php include PATH_VIEW_ADMIN . 'layout/sidebar.php'; ?>
    <main class="maincontain">
      <div id="container">
        <p>Thêm mới đơn</p>
        <div class="form-create">
          <form id="upload-form" enctype="multipart/form-data" method="POST">
            <div class="form">
              <label>Tiêu đề<span>*</span></label>
              <input type="text" name="username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
              <span class="error-message"><?php echo isset($errors['username']) ? $errors['username'] : ''; ?></span>
            </div>
            <div class="form">
              <label>Nội dung</label>
              <textarea name="content"><?php echo isset($_POST['content']) ? htmlspecialchars($_POST['content']) : ''; ?></textarea>
            </div>
            <div class="form">
              <label for="user_type">Người duyệt <span>*</span></label>
              <select name="user_type" id="user_type">
                <option value="" disabled <?php echo !isset($_POST['user_type']) || empty($_POST['user_type']) ? 'selected' : ''; ?>>Chọn giá trị</option>
                <option value="admin" <?php echo isset($_POST['user_type']) && $_POST['user_type'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                <option value="user" <?php echo isset($_POST['user_type']) && $_POST['user_type'] === 'user' ? 'selected' : ''; ?>>User</option>
                <option value="guest" <?php echo isset($_POST['user_type']) && $_POST['user_type'] === 'guest' ? 'selected' : ''; ?>>Guest</option>
              </select>
              <span class="error-message"><?php echo isset($errors['user_type']) ? $errors['user_type'] : ''; ?></span>
            </div>
            <div class="form">
              <label for="department">Loại đơn<span>*</span></label>
              <select name="department" id="department">
                <option value="" disabled <?php echo !isset($_POST['department']) || empty($_POST['department']) ? 'selected' : ''; ?>>Chọn giá trị</option>
                <option value="it" <?php echo isset($_POST['department']) && $_POST['department'] === 'it' ? 'selected' : ''; ?>>IT</option>
                <option value="hr" <?php echo isset($_POST['department']) && $_POST['department'] === 'hr' ? 'selected' : ''; ?>>HR</option>
                <option value="marketing" <?php echo isset($_POST['department']) && $_POST['department'] === 'marketing' ? 'selected' : ''; ?>>Marketing</option>
              </select>
              <span class="error-message"><?php echo isset($errors['department']) ? $errors['department'] : ''; ?></span>
            </div>
            <div class="form">
              <label>Ngày bắt đầu<span>*</span></label>
              <input type="text" id="datetimepicker" name="datestart" value="<?php echo isset($_POST['datestart']) ? htmlspecialchars($_POST['datestart']) : ''; ?>">
              <span class="error-message"><?php echo isset($errors['datestart']) ? $errors['datestart'] : ''; ?></span>
            </div>
            <div class="form">
              <label>Ngày kết thúc<span>*</span></label>
              <input type="text" id="datetimepicker2" name="dateend" value="<?php echo isset($_POST['dateend']) ? htmlspecialchars($_POST['dateend']) : ''; ?>">
              <span class="error-message"><?php echo isset($errors['dateend']) ? $errors['dateend'] : ''; ?></span>
            </div>
            <div class="form file-upload-section">
              <label>Đính kèm<span>*</span></label>
              <label class="custom-file-label" for="file-input">
                <span class="upload-icon"></span>
              </label>
              <input class="custom-file-input" type="file" name="attach" id="file-input" accept="image/*">
              <div class="file-display" style="display: none;">
                <span class="file-name"></span>
                <button type="button" class="remove-file">Xóa</button>
              </div>
              <span class="error-message"><?php echo isset($errors['attach']) ? $errors['attach'] : ''; ?></span>
            </div>
            <div class="button-action">
              <button type="submit" name="next" style="background: #007EC6;">Tiếp theo</button>
              <button type="button" name="clear" style="background: #E2005C;" onclick="clearForm()">Xóa trống</button>
            </div>
          </form>
          <?php if ($success_message): ?>
            <p style="color: green; margin-left: 120px;"><?php echo $success_message; ?></p>
          <?php endif; ?>
        </div>
      </div>
    </main>
  </div>
  <script>
    flatpickr("#datetimepicker", {
      dateFormat: "Y-m-d",
      minDate: "1900-01-01",
      maxDate: "2100-12-31"
    });
    flatpickr("#datetimepicker2", {
      dateFormat: "Y-m-d",
      minDate: "1900-01-01",
      maxDate: "2100-12-31"
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

    function clearForm() {
      document.getElementById('upload-form').reset();
      const fileDisplay = document.querySelector('.file-display');
      const fileLabel = document.querySelector('.custom-file-label');
      fileDisplay.style.display = 'none';
      fileLabel.style.display = 'flex';
    }
  </script>
</body>

</html>