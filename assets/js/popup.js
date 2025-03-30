document.addEventListener("DOMContentLoaded", function () {
  const dialog = document.getElementById("confirm-dialog");
  const wrapper = document.querySelector(".dialog-wrapper");
  const dialogOverlay = document.querySelector(".dialog-overlay");

  if (dialog && wrapper && dialogOverlay) {
    const showConfirmDialog = (show) => {
      if (show) {
        dialogOverlay.style.display = "flex";
        dialog.showModal();
      } else {
        dialogOverlay.style.display = "none";
        dialog.close();
      }
    };

    dialog.addEventListener("click", (e) => {
      if (!wrapper.contains(e.target)) {
        showConfirmDialog(false);
      }
    });

    window.showConfirmDialog = showConfirmDialog;

    window.confirmAndSubmit = function () {
      showConfirmDialog(false);
      document.getElementById("user-form").submit();
    };
  } else {
    console.error(
      "Không tìm thấy dialog, wrapper hoặc dialogOverlay trong DOM."
    );
  }
});
