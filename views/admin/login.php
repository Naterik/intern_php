<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100..900&display=swap" rel="stylesheet" />
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_ASSETS_ADMIN; ?>logins.css">
  <title>Login</title>
</head>

<body>
  <div id="contenter">
    <div class="header">
      <label class="sanshin">Sanshin</label>
      <p class="text-header">Hệ thống quản lý đơn</p>
    </div>
    <div class="body">
      <div class="form">
        <p class="sanshin-it">Sanshin IT Solution</p>
        <form action="<?= BASE_URL_ADMIN . '?action=login' ?>" method="post" id="loginForm">
          <div class="form-input">
            <label class="form-label">Tên đăng nhập<span class="required-mark">*</span></label>
            <input class="input-form" type="text" name="username" />
          </div>
          <div class="form-input">
            <label class="form-label">Mật khẩu<span class="required-mark">*</span></label>
            <input class="input-form" type="password" name="password" />
          </div>
          <div class="error" style="display: none;">
            <?php echo htmlspecialchars($_SESSION['error'] ?? ''); ?>
          </div>
          <div class="button">
            <button class="login-button" type="submit">Login</button>
            <button class="clear-button" type="button" onclick="clearForm()">Clear</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const errorElement = document.querySelector('.error');
      if (errorElement && !errorElement.textContent.trim()) {
        errorElement.style.display = 'none';
      } else if (errorElement) {
        errorElement.style.display = 'block';
        const requiredMarks = document.querySelectorAll('.required-mark');
        requiredMarks.forEach(mark => {
          mark.style.display = 'inline';
        });
      }
    });

    function clearForm() {
      document.getElementById('loginForm').reset();
      const errorElement = document.querySelector('.error');
      if (errorElement) errorElement.style.display = 'none';
      const requiredMarks = document.querySelectorAll('.required-mark');
      requiredMarks.forEach(mark => {
        mark.style.display = 'none';
      });
      window.location.href = "<?= BASE_URL_ADMIN . '?action=clearError' ?>";
    }
  </script>
</body>

</html>