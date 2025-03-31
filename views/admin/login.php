<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100..900&display=swap" rel="stylesheet" />
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_ASSETS_ADMIN; ?>login.css">
  <title>Login</title>
</head>

<body>
  <div id="contenter">
    <div class="header">
      <span class="sanshin">Sanshin</span>
      <p class="text-header">Hệ thống quản lý đơn</p>
    </div>
    <div class="body">
      <div class="form">
        <p class="sanshin-it">Sanshin IT Solution</p>
        <form action="<?= BASE_URL_ADMIN . '?action=login' ?>" method="post" id="loginForm">
          <div class="form-input">
            <span class="form-span">Tên đăng nhập<span style="color: red;">*</span></span>
            <input class="input-form" type="text" name="username"
              value="<?php echo htmlspecialchars($_SESSION['username'] ?? $_POST['username'] ?? ''); ?>" />
          </div>
          <div class="form-input">
            <span class="form-span">Mật khẩu<span style="color: red;">*</span></span>
            <input class="input-form" type="password" name="password"
              value="<?php echo htmlspecialchars($_SESSION['password'] ?? $_POST['password'] ?? ''); ?>" />
          </div>
          <?php if (isset($_SESSION['error'])): ?>
            <div class="error"><?php echo htmlspecialchars($_SESSION['error']); ?></div>
          <?php endif; ?>
          <div class="button">
            <button class="login-button" type="submit">Login</button>
            <button class="clear-button" type="button" onclick="clearForm()">Clear</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    function clearForm() {
      document.getElementById("loginForm").reset();
      const errorElement = document.querySelector(".error");
      if (errorElement) {
        errorElement.remove();
      }
      window.location.href = "<?= BASE_URL_ADMIN . '?action=clearError' ?>";
    }
  </script>
</body>

</html>