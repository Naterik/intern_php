<?php
$currentPage = 'Quản lý người dùng';
$errors = $errors ?? [];
$inputData = $inputData ?? [];
?>

<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_ASSETS_ADMIN; ?>header.css">
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_ASSETS_ADMIN; ?>sidebar.css">
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_ASSETS_ADMIN; ?>create.user.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">
  <title>Chỉnh sửa người dùng</title>
</head>

<body>
  <?php include PATH_VIEW_ADMIN . 'layout/header.php'; ?>
  <div class="wrapper">
    <?php include PATH_VIEW_ADMIN . 'layout/sidebar.php'; ?>
    <main class="maincontain">
      <div id="container">
        <p>Chỉnh sửa người dùng (ID: <?php echo htmlspecialchars($userData['userId'] ?? ''); ?>)</p>
        <div class="form-create">
          <form id="user-form" method="POST" action="<?php echo BASE_URL_ADMIN; ?>?action=users-edit&userId=<?php echo htmlspecialchars($userData['userId'] ?? ''); ?>">
            <!-- Thêm input ẩn để lưu userId -->
            <input type="hidden" name="userId" value="<?php echo htmlspecialchars($userData['userId'] ?? ''); ?>">
            <div class="form">
              <label>Tên đăng nhập<span class="required-mark">*</span></label>
              <input type="text" value="<?php echo htmlspecialchars($inputData['username'] ?? $userData['username'] ?? ''); ?>" disabled>
              <input type="hidden" name="username" value="<?php echo htmlspecialchars($inputData['username'] ?? $userData['username'] ?? ''); ?>">
            </div>
            <div class="form">
              <label>Họ và tên<span class="required-mark">*</span></label>
              <div class="input-form <?php echo isset($errors['name']) ? 'has-error' : ''; ?>">
                <input type="text" name="name" value="<?php echo htmlspecialchars($inputData['name'] ?? $userData['fullname'] ?? ''); ?>">
                <?php if (isset($errors['name'])): ?>
                  <span class="error"><?php echo $errors['name']; ?></span>
                <?php endif; ?>
              </div>
            </div>
            <div class="form">
              <label>Email</label>
              <div class="input-form <?php echo isset($errors['email']) ? 'has-error' : ''; ?>">
                <input type="text" name="email" value="<?php echo htmlspecialchars($inputData['email'] ?? $userData['email'] ?? ''); ?>">
                <?php if (isset($errors['email'])): ?>
                  <span class="error"><?php echo $errors['email']; ?></span>
                <?php endif; ?>
              </div>
            </div>
            <div class="form">
              <label>Mật khẩu</label>
              <div class="input-form <?php echo isset($errors['password']) ? 'has-error' : ''; ?>">
                <input type="password" name="password" value="<?php echo htmlspecialchars($inputData['password'] ?? $userData['password'] ?? ''); ?>">
                <?php if (isset($errors['password'])): ?>
                  <span class="error"><?php echo $errors['password']; ?></span>
                <?php endif; ?>
              </div>
            </div>
            <div class="form">
              <label>Ngày sinh</label>
              <div class="input-form <?php echo isset($errors['birthdate']) ? 'has-error' : ''; ?>">
                <input type="text" id="datetimepicker" name="birthdate" value="<?php echo htmlspecialchars($inputData['birthdate'] ?? $userData['birthDate'] ?? ''); ?>">
                <?php if (isset($errors['birthdate'])): ?>
                  <span class="error"><?php echo $errors['birthdate']; ?></span>
                <?php endif; ?>
              </div>
            </div>
            <div class="form">
              <label>Loại người dùng<span class="required-mark">*</span></label>
              <div class="input-form <?php echo isset($errors['user_type']) ? 'has-error' : ''; ?>">
                <select name="user_type" id="user_type">
                  <option value="admin" <?php echo ($inputData['user_type'] ?? $userData['categoryUser'] ?? '') === 'admin' ? 'selected' : ''; ?>>Admin</option>
                  <option value="manager" <?php echo ($inputData['user_type'] ?? $userData['categoryUser'] ?? '') === 'manager' ? 'selected' : ''; ?>>Manager</option>
                  <option value="user" <?php echo ($inputData['user_type'] ?? $userData['categoryUser'] ?? '') === 'user' ? 'selected' : ''; ?>>User</option>
                </select>
                <?php if (isset($errors['user_type'])): ?>
                  <span class="error"><?php echo $errors['user_type']; ?></span>
                <?php endif; ?>
              </div>
            </div>
            <div class="form">
              <label>Phòng ban<span class="required-mark">*</span></label>
              <div class="input-form <?php echo isset($errors['department']) ? 'has-error' : ''; ?>">
                <select name="department" id="department">
                  <option value="IT" <?php echo ($inputData['department'] ?? $userData['department'] ?? '') === 'IT' ? 'selected' : ''; ?>>IT</option>
                  <option value="HR" <?php echo ($inputData['department'] ?? $userData['department'] ?? '') === 'HR' ? 'selected' : ''; ?>>HR</option>
                  <option value="Marketing" <?php echo ($inputData['department'] ?? $userData['department'] ?? '') === 'Marketing' ? 'selected' : ''; ?>>Marketing</option>
                </select>
                <?php if (isset($errors['department'])): ?>
                  <span class="error"><?php echo $errors['department']; ?></span>
                <?php endif; ?>
              </div>
            </div>
            <div class="form">
              <label>Trạng thái<span class="required-mark">*</span></label>
              <div class="input-form <?php echo isset($errors['status']) ? 'has-error' : ''; ?>">
                <select name="status" id="status">
                  <option value="Đang hoạt động" <?php echo ($inputData['status'] ?? $userData['status'] ?? '') === 'Đang hoạt động' ? 'selected' : ''; ?>>Đang hoạt động</option>
                  <option value="Không hoạt động" <?php echo ($inputData['status'] ?? $userData['status'] ?? '') === 'Không hoạt động' ? 'selected' : ''; ?>>Không hoạt động</option>
                </select>
                <?php if (isset($errors['status'])): ?>
                  <span class="error"><?php echo $errors['status']; ?></span>
                <?php endif; ?>
              </div>
            </div>
            <div class="button-action">
              <button type="submit" name="next" style="background: #007EC6;">Tiếp theo</button>
              <button type="button" name="clear" style="background: #E2005C;" onclick="clearForm()">Xóa trống</button>
            </div>
          </form>
        </div>
      </div>
    </main>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      // Khởi tạo Flatpickr cho trường ngày sinh
      const datetimePicker = document.getElementById("datetimepicker");
      if (datetimePicker) {
        flatpickr("#datetimepicker", {
          dateFormat: "Y-m-d",
          minDate: "1900-01-01",
          maxDate: "2100-12-31",
          enableTime: false,
          defaultDate: "<?php echo htmlspecialchars($inputData['birthdate'] ?? $userData['birthDate'] ?? ''); ?>"
        });
      }

      const form = document.getElementById("user-form");
      const requiredStars = document.querySelectorAll(".required-mark");
      const errors = document.querySelectorAll(".error");

      if (form && requiredStars.length > 0) {
        requiredStars.forEach((star) => {
          star.style.display = "none";
        });

        errors.forEach((error) => {
          const errorText = error.textContent.trim();
          const correspondingStar = error.closest(".form").querySelector(".required-mark");

          if (errorText !== "") {
            correspondingStar.style.display = "inline";
            const parentForm = error.closest(".input-form");
            if (parentForm) parentForm.classList.add("has-error");
          } else {
            correspondingStar.style.display = "none";
            const parentForm = error.closest(".input-form");
            if (parentForm) parentForm.classList.remove("has-error");
          }
        });

        function clearForm() {
          // Lấy tất cả các input và select có thể chỉnh sửa (trừ các input disabled)
          const editableInputs = document.querySelectorAll("input:not([disabled]), select");

          editableInputs.forEach((input) => {
            // Bỏ qua input hiển thị username (vì nó disabled)
            if (input.name === "username" && input.type === "text") {
              return; // Không xóa giá trị của input hiển thị username
            }

            if (input.type === "text" || input.type === "password") {
              input.value = "";
            } else if (input.tagName === "SELECT") {
              input.selectedIndex = 0;
            }
          });

          // Lấy tất cả các input ẩn, nhưng không xóa input ẩn của username và userId
          const hiddenInputs = document.querySelectorAll('input[type="hidden"]');
          hiddenInputs.forEach((input) => {
            if (input.name === "username" || input.name === "userId") {
              return; // Không xóa giá trị của input ẩn username và userId
            }
            input.value = "";
          });

          // Xóa các thông báo lỗi và trạng thái lỗi
          errors.forEach((error) => {
            error.textContent = "";
            const correspondingStar = error.closest(".form").querySelector(".required-mark");
            correspondingStar.style.display = "none";
            const parentForm = error.closest(".input-form");
            if (parentForm) parentForm.classList.remove("has-error");
          });
        }

        // Gắn sự kiện click cho nút "Xóa trống"
        const clearButton = document.querySelector('button[name="clear"]');
        if (clearButton) {
          clearButton.addEventListener("click", clearForm);
        }
      }
      window.errors = <?php echo json_encode($errors); ?>;
    });
  </script>
</body>

</html>