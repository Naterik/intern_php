<!-- popup.php -->

<div class="dialog-overlay" style="display: none;">
  <div class="show-pop">
    <dialog id="confirm-dialog">
      <div class="dialog-wrapper">
        <div class="dialog-header">
          <span>Thông báo</span>
          <img src="<?php echo BASE_ASSETS_UPLOAD; ?>img/material-symbols_close-rounded.png" onclick="showConfirmDialog(false)" alt="Close">
        </div>
        <form id="confirm-form">
          <label>Bạn có chắc chắn muốn lưu?</label>
          <div class="modal-buttons">
            <button type="button" onclick="confirmAndSubmit()">OK</button>
            <button type="button" onclick="showConfirmDialog(false)">Cancel</button>
          </div>
        </form>
      </div>
    </dialog>
  </div>
</div>