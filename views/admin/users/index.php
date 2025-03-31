<?php
$currentPage = 'Quản lý người dùng';
?>
<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Quản lý người dùng</title>
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_ASSETS_ADMIN; ?>popups.css">
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_ASSETS_ADMIN; ?>header.css">
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_ASSETS_ADMIN; ?>sidebar.css">
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_ASSETS_ADMIN; ?>user.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>

<body>
  <?php include PATH_VIEW_ADMIN . 'layout/header.php'; ?>
  <div class="wrapper">
    <?php include PATH_VIEW_ADMIN . 'layout/sidebar.php'; ?>
    <main class="maincontain">
      <div class="body">
        <div class="action">
          <div class="search">
            <span>Mã/Tên user</span>
            <form method="GET" action="<?php echo BASE_URL_ADMIN; ?>">
              <input type="hidden" name="action" value="users-index">
              <input class="search-input" type="text" name="search" value="<?php echo htmlspecialchars($searchTerm ?? ''); ?>" placeholder="Nhập từ khóa...">
              <button type="submit" class="action-button-search">Tìm kiếm</button>
            </form>
          </div>
          <div class="action-button">
            <a href="<?php echo BASE_URL_ADMIN; ?>?action=users-create">
              <button class="action-button-create" type="button">Thêm mới</button>
            </a>
            <button class="action-button-delete" type="button" onclick="confirmMultiDelete()">Xóa nhiều</button>
          </div>
        </div>

        <!-- Form chứa danh sách user -->
        <form id="user-form" method="POST" action="">
          <input type="hidden" name="action" id="form-action" value="">
          <input type="hidden" name="confirm" id="confirm" value="true">
          <!-- Input ẩn chứa toàn bộ userId đã tick trên các trang -->
          <input type="hidden" name="userIds" id="all-selected-users" value="">
          <table>
            <thead>
              <tr>
                <th>
                  <label class="checkbox-label">
                    <input class="checkbox select-all" type="checkbox">
                    <span class="checkmark"></span>
                  </label>
                </th>
                <th>Mã người dùng</th>
                <th>Tên người dùng</th>
                <th>Ngày lập</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($users)): ?>
                <tr>
                  <td colspan="6">Không có người dùng nào.</td>
                </tr>
              <?php else: ?>
                <?php foreach ($users as $user): ?>
                  <tr>
                    <td class="checkbox-td">
                      <label class="checkbox-label">
                        <input class="checkbox user-checkbox" type="checkbox" name="dummyUserIds[]" value="<?php echo $user['userId']; ?>">
                        <span class="checkmark"></span>
                      </label>
                    </td>
                    <td><?php echo htmlspecialchars($user['userId']); ?></td>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo date('Y-m-d', strtotime($user['created_at'])); ?></td>
                    <td><?php echo htmlspecialchars($user['status'] ?? 'Đang hoạt động'); ?></td>
                    <td>
                      <a href="<?php echo BASE_URL_ADMIN; ?>?action=users-edit&userId=<?php echo $user['userId']; ?>">
                        <button type="button" class="button-table button-edit">Sửa</button>
                      </a>
                      <button type="button" class="button-table button-delete" onclick="confirmDeleteSingle('<?php echo $user['userId']; ?>')">Xóa</button>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
          <!-- Phần phân trang -->
          <footer>
            <div class="pagination_section">
              <div class="page-pre">
                <?php if ($page > 1): ?>
                  <a href="<?php echo BASE_URL_ADMIN; ?>?action=users-index&page=1">
                    <img src="<?php echo BASE_ASSETS_UPLOAD; ?>img/Arrow left@3x.png" alt="First">
                  </a>
                  <a href="<?php echo BASE_URL_ADMIN; ?>?action=users-index&page=<?php echo ($page - 1); ?>">Previous</a>
                <?php endif; ?>
              </div>
              <div class="page-number">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                  <a class="page <?php echo ($page == $i) ? 'active' : ''; ?>" href="<?php echo BASE_URL_ADMIN; ?>?action=users-index&page=<?php echo $i; ?>&search=<?php echo urlencode($searchTerm ?? ''); ?>">
                    <?php echo $i; ?>
                  </a>
                <?php endfor; ?>
              </div>
              <div class="page-next">
                <?php if ($page < $totalPages): ?>
                  <a href="<?php echo BASE_URL_ADMIN; ?>?action=users-index&page=<?php echo ($page + 1); ?>">Next</a>
                  <a href="<?php echo BASE_URL_ADMIN; ?>?action=users-index&page=<?php echo $totalPages; ?>">
                    <img src="<?php echo BASE_ASSETS_UPLOAD; ?>img/Arrow right.png" alt="Last">
                  </a>
                <?php endif; ?>
              </div>
            </div>
          </footer>
        </form>
      </div>
    </main>
  </div>

  <?php require_once PATH_VIEW_ADMIN . 'layout/popup.php'; ?>

  <script src="<?php echo BASE_ASSETS_JS; ?>popup.js"></script>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const checkboxes = document.querySelectorAll(".user-checkbox");
      const selectAllCheckbox = document.querySelector(".select-all");

      // Lấy danh sách userId đã chọn từ localStorage
      let selectedUsers = JSON.parse(localStorage.getItem("selectedUsers")) || [];

      // Cập nhật trạng thái checkbox từ localStorage
      checkboxes.forEach((checkbox) => {
        if (selectedUsers.includes(checkbox.value)) {
          checkbox.checked = true;
        }
        checkbox.addEventListener("change", function() {
          if (this.checked) {
            if (!selectedUsers.includes(this.value)) {
              selectedUsers.push(this.value);
            }
          } else {
            selectedUsers = selectedUsers.filter((id) => id !== this.value);
          }
          localStorage.setItem("selectedUsers", JSON.stringify(selectedUsers));
        });
      });

      // Xử lý "Chọn tất cả"
      if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener("change", function() {
          checkboxes.forEach((cb) => {
            cb.checked = this.checked;
            if (this.checked) {
              if (!selectedUsers.includes(cb.value)) {
                selectedUsers.push(cb.value);
              }
            } else {
              // Nếu bỏ chọn tất cả, xóa hết danh sách
              selectedUsers = [];
            }
          });
          localStorage.setItem("selectedUsers", JSON.stringify(selectedUsers));
        });
      }
    });

    function confirmDeleteSingle(userId) {
      // Xử lý xóa 1 user (giữ nguyên logic hiện tại)
      document.getElementById('user-form').action = '?action=users-delete';
      document.getElementById('form-action').value = 'users-delete';
      // Bạn có thể đặt 1 input ẩn cho singleUserId nếu cần
      window.showConfirmDialog(true);
    }

    function confirmMultiDelete() {
      const selectedUsers = JSON.parse(localStorage.getItem("selectedUsers")) || [];
      if (selectedUsers.length === 0) {
        alert('Vui lòng chọn ít nhất một người dùng để xóa.');
        return;
      }
      // Trước khi submit, gán danh sách đã chọn vào input ẩn
      document.getElementById('all-selected-users').value = JSON.stringify(selectedUsers);
      document.getElementById('user-form').action = '?action=users-multidelete';
      document.getElementById('form-action').value = 'users-multidelete';
      window.showConfirmDialog(true);
    }
  </script>
</body>

</html>