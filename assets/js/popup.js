// popup.js
document.addEventListener("DOMContentLoaded", function () {
  const dialog = document.getElementById("confirm-dialog");
  const dialogOverlay = document.querySelector(".dialog-overlay");
  const wrapper = document.querySelector(".dialog-wrapper"); // Khai báo wrapper

  if (dialog && wrapper && dialogOverlay) {
    // Định nghĩa hàm showConfirmDialog toàn cục
    window.showConfirmDialog = function (show) {
      if (show) {
        dialogOverlay.style.display = "block"; // Hiển thị overlay
        dialog.showModal(); // Hiển thị dialog
      } else {
        dialog.close(); // Đóng dialog
        dialogOverlay.style.display = "none"; // Ẩn overlay
      }
    };

    // Xử lý click ngoài dialog để đóng
    dialog.addEventListener("click", (e) => {
      if (!wrapper.contains(e.target)) {
        window.showConfirmDialog(false);
      }
    });

    // Định nghĩa hàm confirmAndSubmit toàn cục
    window.confirmAndSubmit = function () {
      window.showConfirmDialog(false); // Đóng dialog
      // Tìm form động dựa trên trang hiện tại
      const form =
        document.getElementById("letter-form") ||
        document.getElementById("user-form");
      if (form) {
        form.submit(); // Gửi form tương ứng
      } else {
        console.error("Không tìm thấy form để gửi!");
      }
    };
  } else {
    console.error(
      "Không tìm thấy dialog, wrapper hoặc dialogOverlay trong DOM."
    );
  }
});
