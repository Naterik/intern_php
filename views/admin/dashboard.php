<!DOCTYPE html>
<html lang="vi">
<?php
$currentPage = 'Dashboard';
?>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_ASSETS_ADMIN; ?>header.css">
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_ASSETS_ADMIN; ?>sidebar.css">
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_ASSETS_ADMIN; ?>dashboards.css">
  <title>
    <?php if (isset($_SESSION["username"])) {
      $username = $_SESSION["username"];
      echo "Welcome $username";
    } else {
      echo "Sanshin - Hệ thống quản lý đơn";
    } ?>
  </title>
</head>

<body>
  <?php include PATH_VIEW_ADMIN . 'layout/header.php'; ?>

  <div class="wrapper">
    <?php include PATH_VIEW_ADMIN . 'layout/sidebar.php'; ?>

    <main class="maincontain">
      <div class="body">
        <table>
          <tr>
            <th>STT</th>
            <th>Tiêu đề</th>
            <th>Loại đơn</th>
            <th>Ngày tạo</th>
            <th>Trạng thái</th>
            <th>Mô tả</th>
          </tr>
          <?php if (empty($letters)): ?>
            <tr>
              <td colspan="7">Không có đơn nào.</td>
            </tr>
          <?php else: ?>
            <?php foreach ($letters as $index => $letter): ?>

              <tr class="<?php echo $rowClass; ?>">
                <td><?php echo $index + 1; ?></td>
                <td><?php echo htmlspecialchars($letter['title']); ?></td>
                <td><?php echo htmlspecialchars($letter['typesOfApplication']); ?></td>
                <td><?php echo date('Y-m-d', strtotime($letter['startDate'])); ?></td>
                <td><?php echo htmlspecialchars($letter['status']); ?></td>
                <td><?php echo htmlspecialchars($letter['content']); ?></td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </table>
      </div>
    </main>
  </div>
</body>

</html>