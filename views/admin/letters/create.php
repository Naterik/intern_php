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
        <div class="bodycontain">
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
                    <option value="" disabled selected>Chọn người duyệt</option>
                    <?php
                    $users = $this->userModel->getApproveUsers();
                    foreach ($users as $user) {
                      $selected = isset($inputData['approver']) && $inputData['approver'] == $user['userId'] ? 'selected' : '';
                      echo '<option value="' . htmlspecialchars($user['userId']) . '" ' . $selected . '>'
                        . htmlspecialchars($user['fullname']) . ' </option>';
                    }
                    ?>
                  </select>
                  <?php if (isset($errors['approver'])): ?>
                    <span class="error" data-field="approver"><?php echo $errors['approver']; ?></span>
                  <?php endif; ?>
                </div>
              </div>
              <div class="form">
                <label for="typesOfApplication">Loại đơn<span class="required-star">*</span></label>
                <div class="input-form <?php echo isset($errors['typesOfApplication']) ? 'has-error' : ''; ?>">
                  <select name="typesOfApplication" id="typesOfApplication">
                    <option value="" disabled <?php echo empty($inputData['typesOfApplication']) ? 'selected' : ''; ?>>Chọn lý do</option>
                    <option value="Nghỉ ốm" <?php echo ($inputData['typesOfApplication'] ?? '') === 'Nghỉ ốm' ? 'selected' : ''; ?>>Nghỉ ốm</option>
                    <option value="Tai nạn" <?php echo ($inputData['typesOfApplication'] ?? '') === 'Tai nạn' ? 'selected' : ''; ?>>Tai nạn</option>
                    <option value="Tăng ca" <?php echo ($inputData['typesOfApplication'] ?? '') === 'Tăng ca' ? 'selected' : ''; ?>>Tăng ca</option>
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
                  <?php if (isset($inputData['attachment']) && !empty($inputData['attachment'])): ?>
                    <div class="file-display" style="display: block;">
                      <span class="file-name"><?php echo htmlspecialchars(basename($inputData['attachment'])); ?></span>
                    </div>
                    <input type="hidden" name="attachment" value="<?php echo htmlspecialchars($inputData['attachment']); ?>">
                  <?php else: ?>
                    <label class="custom-file-label" for="file-input">
                      <span class="upload-icon"></span>
                    </label>
                    <input class="custom-file-input" type="file" name="attach" id="file-input" accept="image/*">
                  <?php endif; ?>
                  <?php if (isset($errors['attachment'])): ?>
                    <span class="error" data-field="attachment"><?php echo $errors['attachment']; ?></span>
                  <?php endif; ?>
                </div>
              </div>
              <div class="button-action">
                <button type="submit" name="next" style="background: #007EC6;">Tiếp theo</button>
                <button type="submit" name="clear" style="background: #E2005C;">Xóa trống</button>
              </div>
          </div>

          </form>
        </div>
      </div>
  </div>
  </main>
  </div>
  <script>
    window.errors = <?php echo json_encode($errors); ?>;
  </script>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
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

      const requiredStars = document.querySelectorAll(".required-star");
      requiredStars.forEach((star) => {
        star.style.display = "none";
      });

      // Xử lý khi upload file mới
      const fileInput = document.getElementById("file-input");
      if (fileInput) {
        fileInput.addEventListener("change", function(event) {
          const fileDisplay = document.querySelector(".file-display") || document.createElement("div");
          fileDisplay.className = "file-display";
          const fileNameSpan = document.querySelector(".file-name") || document.createElement("span");
          fileNameSpan.className = "file-name";
          const fileLabel = document.querySelector(".custom-file-label");

          if (event.target.files.length > 0) {
            fileNameSpan.textContent = event.target.files[0].name;
            fileDisplay.appendChild(fileNameSpan);
            fileDisplay.appendChild(document.createElement("button")).textContent = "Xóa";
            fileDisplay.lastChild.type = "button";
            fileDisplay.lastChild.className = "remove-file";
            fileDisplay.style.display = "block";
            fileLabel.style.display = "none";
            event.target.parentElement.appendChild(fileDisplay);
          }
        });
      }

      // Xử lý xóa file
      document.querySelectorAll(".remove-file").forEach(button => {
        button.addEventListener("click", function() {
          const fileDisplay = this.parentElement;
          const inputForm = fileDisplay.parentElement;
          const hiddenInput = inputForm.querySelector("input[name='attachment']");
          const fileLabel = inputForm.querySelector(".custom-file-label") || document.createElement("label");
          const newFileInput = document.createElement("input");

          // Xóa input ẩn nếu có
          if (hiddenInput) {
            hiddenInput.remove();
          }

          // Xóa phần hiển thị file
          fileDisplay.remove();

          // Hiển thị lại input file
          fileLabel.className = "custom-file-label";
          fileLabel.htmlFor = "file-input";
          fileLabel.innerHTML = '<span class="upload-icon"></span>';
          newFileInput.type = "file";
          newFileInput.name = "attach";
          newFileInput.id = "file-input";
          newFileInput.className = "custom-file-input";
          newFileInput.accept = "image/*";

          inputForm.appendChild(fileLabel);
          inputForm.appendChild(newFileInput);
        });
      });

      // Xử lý nút "Xóa trống"
      document.querySelector('button[name="clear"]').addEventListener("click", function() {
        const form = document.getElementById("upload-form");
        form.reset();

        const fileDisplay = document.querySelector(".file-display");
        const fileLabel = document.querySelector(".custom-file-label");
        if (fileDisplay) fileDisplay.style.display = "none";
        if (fileLabel) fileLabel.style.display = "flex";

        const hiddenInput = document.querySelector("input[name='attachment']");
        if (hiddenInput) hiddenInput.remove();

        document.querySelectorAll(".error").forEach((error, index) => {
          error.textContent = "";
          requiredStars[index].style.display = "none";
          error.closest(".input-form").classList.remove("has-error");
        });
      });

      // Xử lý lỗi từ server
      const errors = document.querySelectorAll(".error");
      errors.forEach((error, index) => {
        const errorText = error.textContent.trim();
        const correspondingStar = requiredStars[index];
        const parentForm = error.closest(".input-form");

        if (errorText !== "") {
          correspondingStar.style.display = "inline";
          parentForm.classList.add("has-error");
        } else {
          correspondingStar.style.display = "none";
          parentForm.classList.remove("has-error");
        }
      });
    });
  </script>
</body>

</html>