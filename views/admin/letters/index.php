<?php
$currentPage = 'Quản lý đơn';
?>

<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_ASSETS_ADMIN; ?>header.css">
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_ASSETS_ADMIN; ?>sidebar.css">
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_ASSETS_ADMIN; ?>letter.css">
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_ASSETS_ADMIN; ?>popups.css">
  <title>Quản lý đơn</title>
</head>

<body>
  <?php include PATH_VIEW_ADMIN . 'layout/header.php'; ?>
  <div class="wrapper">
    <?php include PATH_VIEW_ADMIN . 'layout/sidebar.php'; ?>
    <main class="maincontain">
      <div class="body">
        <div class="action">
          <div class="search">
            <span>Tên user/Loại đơn/Nội dung</span>
            <form method="GET" action="<?php echo BASE_URL_ADMIN; ?>">
              <input type="hidden" name="action" value="letters-index">
              <input class="search-input" type="text" name="search" value="<?php echo htmlspecialchars($searchTerm ?? ''); ?>" placeholder="Nhập từ khóa..." />
              <button class="action-button-search" type="submit">Tìm kiếm</button>
            </form>
          </div>
          <div class="action-button">
            <a href="<?php echo BASE_URL_ADMIN; ?>?action=letters-create"><button class="action-button-create">Thêm mới đơn</button></a>
          </div>
        </div>

        <table>
          <thead>
            <tr>
              <th>STT</th>
              <th>Người dùng</th>
              <th>Loại đơn</th>
              <th>Ngày lập</th>
              <th>Trạng thái</th>
              <th>Ngày duyệt</th>
              <th>Mô tả</th>
            </tr>
          </thead>
          <tbody>
            <?php if (isset($noResults) && $noResults): ?>
              <tr>
                <td colspan="7">Không tìm thấy kết quả nào.</td>
              </tr>
            <?php elseif (empty($data)): ?>
              <tr>
                <td colspan="7">Không có đơn nào.</td>
              </tr>
            <?php else: ?>
              <?php foreach ($data as $index => $letter): ?>
                <?php
                $status = $letter['status'];
                $statusText = $status == 'đơn mới' ? 'Đơn mới' : ($status == 'đã duyệt' ? 'Đã duyệt' : 'Đã hủy');
                $rowClass = $status == 'đơn mới' ? 'status-new' : ($status == 'đã hủy' ? 'status-canceled' : '');
                ?>
                <tr class="<?php echo $rowClass; ?>">
                  <td><?php echo $offset + $index + 1; ?></td>
                  <td><?php echo htmlspecialchars($letter['username'] ?? ''); ?></td>
                  <td><?php echo htmlspecialchars($letter['typesOfApplication'] ?? ''); ?></td>
                  <td><?php echo htmlspecialchars($letter['startDate'] ?? ''); ?></td>
                  <td style="font-weight: 600;"><?php echo $statusText; ?></td>
                  <td><?php echo htmlspecialchars($letter['approvalDate'] ?? ''); ?></td>
                  <td>
                    <div class="table-td">
                      <span><?php echo htmlspecialchars($letter['content'] ?? ''); ?></span>
                      <?php if (isset($_SESSION['categoryUser']) && strcasecmp($_SESSION['categoryUser'], 'admin') === 0 || strcasecmp($_SESSION['categoryUser'], 'manager') === 0): ?>
                        <?php if ($status == 'đơn mới'): ?>
                          <div class="button-table-action">
                            <div class="button-table-action">
                              <a href="<?php echo BASE_URL_ADMIN; ?>?action=letters-approve&letterId=<?php echo $letter['letterId']; ?>">
                                <button class="button-table button-approve">Duyệt</button>
                              </a>
                              <button class="button-table button-cancel" onclick="showCancelDialog(<?php echo $letter['letterId']; ?>)">Hủy</button>
                            </div>
                          </div>
                        <?php endif; ?>
                      <?php endif; ?>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>

        <footer>
          <div class="pagination_section">
            <div class="page-pre">
              <?php if ($page > 1): ?>
                <a href="<?php echo BASE_URL_ADMIN; ?>?action=letters-index&page=1<?php echo $searchTerm ? '&search=' . urlencode($searchTerm) : ''; ?>">
                  <img src="<?php echo BASE_ASSETS_UPLOAD; ?>img/Arrow left@3x.png" alt="Previous">
                </a>
                <a href="<?php echo BASE_URL_ADMIN; ?>?action=letters-index&page=<?php echo ($page - 1); ?><?php echo $searchTerm ? '&search=' . urlencode($searchTerm) : ''; ?>">Previous</a>
              <?php endif; ?>
            </div>
            <div class="page-number">
              <?php if (isset($totalPages)): ?>
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                  <a class="page <?php echo $page == $i ? 'active' : ''; ?>"
                    href="<?php echo BASE_URL_ADMIN; ?>?action=letters-index&page=<?php echo $i; ?><?php echo $searchTerm ? '&search=' . urlencode($searchTerm) : ''; ?>">
                    <?php echo $i; ?>
                  </a>
                <?php endfor; ?>
              <?php endif; ?>
            </div>
            <div class="page-next">
              <?php if ($page < $totalPages): ?>
                <a href="<?php echo BASE_URL_ADMIN; ?>?action=letters-index&page=<?php echo ($page + 1); ?><?php echo $searchTerm ? '&search=' . urlencode($searchTerm) : ''; ?>">Next</a>
                <a href="<?php echo BASE_URL_ADMIN; ?>?action=letters-index&page=<?php echo $totalPages; ?><?php echo $searchTerm ? '&search=' . urlencode($searchTerm) : ''; ?>">
                  <img src="<?php echo BASE_ASSETS_UPLOAD; ?>img/Arrow right.png" alt="Next">
                </a>
              <?php endif; ?>
            </div>
          </div>
        </footer>

        <!-- Dialog hủy đơn -->
        <dialog id="cancel-dialog">
          <div class="dialog-wrapper">
            <div class="dialog-header">
              <span>Thông báo</span>
              <img src="<?php echo BASE_ASSETS_UPLOAD; ?>img/material-symbols_close-rounded.png" onclick="showCancelDialog(null)" alt="Close" />
            </div>
            <form id="cancel-form" method="POST" action="<?php echo BASE_URL_ADMIN; ?>?action=letters-approve">
              <input type="hidden" name="letterId" id="cancel-letter-id">
              <input type="hidden" name="status" value="đã hủy">
              <label>Lý do hủy đơn <span style="color:red">*</span></label>
              <input type="text" name="reason" required>
              <div class="modal-buttons">
                <button class="modal-buttons-ok" type="submit">OK</button>
              </div>
            </form>
          </div>
        </dialog>
      </div>
    </main>
  </div>

  <script>
    function showCancelDialog(letterId) {
      const dialog = document.getElementById('cancel-dialog');
      const letterIdInput = document.getElementById('cancel-letter-id');
      if (letterId) {
        letterIdInput.value = letterId;
        dialog.showModal();
      } else {
        dialog.close();
      }
    }

    // Thêm script để hiển thị alert và redirect nếu có lỗi
    <?php if (isset($errorToDisplay)): ?>
      alert('<?php echo addslashes($errorToDisplay); ?>');
      window.location.href = '<?php echo BASE_URL_ADMIN; ?>?action=letters-index';
    <?php endif; ?>
  </script>
</body>

</html>