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
        <form action="<?= BASE_URL_ADMIN . '?action=login' ?>" method="post">
          <div class="form-input">
            <span class="form-span">Tên đăng nhập<span style="color: red;">*</span></span>
            <input class="input-form" type="text" name="username" placeholder="Tên đăng nhập" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" />
          </div>
          <div class="form-input">
            <span class="form-span">Mật khẩu<span style="color: red;">*</span></span>
            <input class="input-form" type="password" name="password" placeholder="Mật khẩu" />
          </div>
          <?php if (isset($_SESSION['msg'])): ?>
            <div class="error"><?php echo htmlspecialchars($_SESSION['msg']);
                                unset($_SESSION['msg']); ?></div>
          <?php endif; ?>
          <?php if (isset($_SESSION['error'])): ?>
            <div class="error"><?php echo htmlspecialchars($_SESSION['error']);
                                unset($_SESSION['error']); ?></div>
          <?php endif; ?>
          <div class="button">
            <button class="login-button" type="submit">Login</button>
            <button class="clear-button" type="reset">Clear</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</body>

</html>