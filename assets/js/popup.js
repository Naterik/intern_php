// popup.js
document.addEventListener("DOMContentLoaded", function () {
  const dialog = document.getElementById("confirm-dialog");
  const dialogOverlay = document.querySelector(".dialog-overlay");
  const wrapper = document.querySelector(".dialog-wrapper");

  if (dialog && wrapper && dialogOverlay) {
    window.showConfirmDialog = function (show) {
      if (show) {
        dialogOverlay.style.display = "block";
        dialog.showModal();
      } else {
        dialog.close();
        dialogOverlay.style.display = "none";
      }
    };

    dialog.addEventListener("click", (e) => {
      if (!wrapper.contains(e.target)) {
        window.showConfirmDialog(false);
      }
    });

    window.confirmAndSubmit = function () {
      window.showConfirmDialog(false);
      const form =
        document.getElementById("letter-form") ||
        document.getElementById("user-form");
      if (form) {
        form.submit();
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
