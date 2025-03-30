<div class="sidebar-contain">

  <div class="sider">
    <div class="sidebarlink <?php echo $currentPage == 'Dashboard' ? 'active' : ''; ?>">
      <div class="sidebar-layer">
        <a class="link" href="<?php echo BASE_URL_ADMIN; ?>?action=dashboard">Dashboard</a>
      </div>
    </div>
  </div>
  <div class="sider">
    <div class="sidebarlink <?php echo $currentPage == 'Quản lý người dùng' ? 'active' : ''; ?>">
      <div class="sidebar-layer">
        <a class="link" href="<?php echo BASE_URL_ADMIN; ?>?action=users-index">Quản lý người dùng</a>
      </div>
    </div>
  </div>
  <div class="sider">
    <div class="sidebarlink <?php echo $currentPage == 'Quản lý đơn' ? 'active' : ''; ?>">
      <div class="sidebar-layer">
        <a class="link" href="<?php echo BASE_URL_ADMIN; ?>?action=letters-index">Quản lý đơn</a>
      </div>
    </div>
  </div>
  <div class="sider">
    <div class="sidebarlink <?php echo $currentPage == 'Đăng xuất' ? 'active' : ''; ?>">
      <div class="sidebar-layer">
        <a class="link" href="<?php echo BASE_URL_ADMIN; ?>?action=logout">Đăng xuất</a>
      </div>
    </div>
  </div>
</div>