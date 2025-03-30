<?php
$currentPage = 'Quản lý đơn';
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
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_ASSETS_ADMIN; ?>create.letter.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">
  <title>Thêm mới đơn</title>
</head>

<body>
  <?php include PATH_VIEW_ADMIN . 'layout/header.php'; ?>
  <div class="wrapper">
    <?php include PATH_VIEW_ADMIN . 'layout/sidebar.php'; ?>
    <main class="maincontain">
      <div id="container">
        <p>Thêm mới đơn</p>
        <div class="form-create">
          <form id="upload-form" enctype="multipart/form-data" method="POST" action="<?php echo BASE_URL_ADMIN; ?>?action=letters-create">
            <!-- Input ẩn cho userId -->
            <input type="hidden" name="userId" value="<?php echo htmlspecialchars($_SESSION['userId'] ?? ''); ?>">
            <div class="form">
              <label>Tiêu đề<span class="required-star">*</span></label>
              <div class="input-form <?php echo isset($errors['title']) ? 'has-error' : ''; ?>">
                <input type="text" name="title" value="<?php echo htmlspecialchars($inputData['title'] ?? ''); ?>">
                <?php if (isset($errors['title'])): ?>
                  <span class="error" data-field="title"><?php echo $errors['title']; ?></span>
                <?php endif; ?>
              </div>
            </div>
            <div class="form">
              <label>Nội dung</label>
              <div class="input-form">
                <textarea name="content"><?php echo htmlspecialchars($inputData['content'] ?? ''); ?></textarea>
              </div>
            </div>
            <div class="form">
              <label for="approver">Người duyệt<span class="required-star">*</span></label>
              <div class="input-form <?php echo isset($errors['approver']) ? 'has-error' : ''; ?>">
                <select name="approver" id="approver">
                  <option value="" disabled <?php echo empty($inputData['approver']) ? 'selected' : ''; ?>>Chọn người duyệt</option>
                  <?php
                  $admins = $this->letterModel->getAdminUsers();
                  foreach ($admins as $admin) {
                    echo '<option value="' . $admin['userId'] . '" ' . ($inputData['approver'] == $admin['userId'] ? 'selected' : '') . '>' . htmlspecialchars($admin['fullname']) . '</option>';
                  }
                  ?>
                </select>
                <?php if (isset($errors['approver'])): ?>
                  <span class="error"><?php echo $errors['approver']; ?></span>
                <?php endif; ?>
              </div>
            </div>
            <div class="form">
              <label for="typesOfApplication">Loại đơn<span class="required-star">*</span></label>
              <div class="input-form <?php echo isset($errors['typesOfApplication']) ? 'has-error' : ''; ?>">
                <select name="typesOfApplication" id="typesOfApplication">
                  <option value="" disabled <?php echo empty($inputData['typesOfApplication']) ? 'selected' : ''; ?>>Chọn lý do</option>
                  <option value="Ốm đau" <?php echo ($inputData['typesOfApplication'] ?? '') === 'Ốm đau' ? 'selected' : ''; ?>>Ốm đau</option>
                  <option value="Tai nạn" <?php echo ($inputData['typesOfApplication'] ?? '') === 'Tai nạn' ? 'selected' : ''; ?>>Tai nạn</option>
                  <option value="Thai sản" <?php echo ($inputData['typesOfApplication'] ?? '') === 'Thai sản' ? 'selected' : ''; ?>>Thai sản</option>
                  <option value="Con nhỏ ốm" <?php echo ($inputData['typesOfApplication'] ?? '') === 'Con nhỏ ốm' ? 'selected' : ''; ?>>Con nhỏ ốm</option>
                </select>
                <?php if (isset($errors['typesOfApplication'])): ?>
                  <span class="error" data-field="typesOfApplication"><?php echo $errors['typesOfApplication']; ?></span>
                <?php endif; ?>
              </div>
            </div>
            <div class="form">
              <label>Ngày bắt đầu<span class="required-star">*</span></label>
              <div class="input-form <?php echo isset($errors['startDate']) ? 'has-error' : ''; ?>">
                <input type="text" id="datetimepicker" name="startDate" value="<?php echo htmlspecialchars($inputData['startDate'] ?? ''); ?>">
                <?php if (isset($errors['startDate'])): ?>
                  <span class="error" data-field="startDate"><?php echo $errors['startDate']; ?></span>
                <?php endif; ?>
              </div>
            </div>
            <div class="form">
              <label>Ngày kết thúc<span class="required-star">*</span></label>
              <div class="input-form <?php echo isset($errors['endDate']) ? 'has-error' : ''; ?>">
                <input type="text" id="datetimepicker2" name="endDate" value="<?php echo htmlspecialchars($inputData['endDate'] ?? ''); ?>">
                <?php if (isset($errors['endDate'])): ?>
                  <span class="error" data-field="endDate"><?php echo $errors['endDate']; ?></span>
                <?php endif; ?>
              </div>
            </div>
            <div class="form file-upload-section">
              <label>Đính kèm<span class="required-star">*</span></label>
              <div class="input-form <?php echo isset($errors['attachment']) ? 'has-error' : ''; ?>">
                <label class="custom-file-label" for="file-input">
                  <span class="upload-icon"></span>
                </label>
                <input class="custom-file-input" type="file" name="attach" id="file-input" accept="image/*">
                <div class="file-display" style="display: none;">
                  <span class="file-name"></span>
                  <button type="button" class="remove-file">Xóa</button>
                </div>
                <?php if (isset($errors['attachment'])): ?>
                  <span class="error" data-field="attachment"><?php echo $errors['attachment']; ?></span>
                <?php endif; ?>
              </div>
            </div>
            <div class="button-action">
              <button type="submit" name="next" style="background: #007EC6;">Tiếp theo</button>
              <button type="submit" name="clear" style="background: #E2005C;">Xóa trống</button>
            </div>
          </form>
        </div>
      </div>
    </main>
  </div>
  <style>
    .error {
      color: red;
      font-size: 0.9em;
      margin-top: 10px;
      margin-bottom: 20px;
      display: block;
    }

    input:focus,
    select:focus {
      border: 2px solid #007EC6 !important;
      outline: none;
    }

    .has-error input,
    .has-error select,
    .has-error .custom-file-label {
      border: 2px solid #FF0000 !important;
    }

    input,
    select {
      border: 1px solid #ccc !important;
      transition: border-color 0.3s ease;
    }

    .required-star {
      color: red;
    }
  </style>
  <script>
    window.errors = <?php echo json_encode($errors); ?>;
  </script>
  <script src="<?php echo BASE_ASSETS_JS; ?>letter.js"></script>
</body>

</html>