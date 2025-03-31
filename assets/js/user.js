document.addEventListener("DOMContentLoaded", function () {
  // Khởi tạo Flatpickr cho trường datetime (nếu có)
  const datetimePicker = document.getElementById("datetimepicker");
  if (datetimePicker) {
    flatpickr("#datetimepicker", {
      dateFormat: "Y-m-d",
      minDate: "1900-01-01",
      maxDate: "2100-12-31",
      enableTime: false,
    });
  }

  // Xử lý form nhập liệu (nếu có, ví dụ: trang create.users.php)
  const form = document.getElementById("user-form");
  const requiredStars = document.querySelectorAll(".required-mark");
  const errors = document.querySelectorAll(".error");

  if (form && requiredStars.length > 0) {
    // Ẩn các required-star ban đầu
    requiredStars.forEach((star) => {
      star.style.display = "none";
    });

    // Hiển thị required-star và trạng thái has-error nếu có lỗi
    errors.forEach((error) => {
      const errorText = error.textContent.trim();
      const correspondingStar = error
        .closest(".form")
        .querySelector(".required-mark");

      if (errorText !== "") {
        correspondingStar.style.display = "inline";
        const parentForm = error.closest(".input-form");
        if (parentForm) parentForm.classList.add("has-error");
      } else {
        correspondingStar.style.display = "none";
        const parentForm = error.closest(".input-form");
        if (parentForm) parentForm.classList.remove("has-error");
      }
    });

    // Hàm xóa form và ẩn lỗi
    function clearForm() {
      // Xóa dữ liệu trong các trường input có thể chỉnh sửa
      const editableInputs = document.querySelectorAll(
        "input:not([disabled]), select"
      );
      editableInputs.forEach((input) => {
        if (input.type === "text" || input.type === "password") {
          input.value = "";
        } else if (input.tagName === "SELECT") {
          input.selectedIndex = 0; // Chọn lại option đầu tiên
        }
      });

      // Xóa dữ liệu trong trường hidden
      const hiddenInputs = document.querySelectorAll('input[type="hidden"]');
      hiddenInputs.forEach((input) => {
        input.value = "";
      });

      errors.forEach((error) => {
        error.textContent = "";
        const correspondingStar = error
          .closest(".form")
          .querySelector(".required-mark");
        correspondingStar.style.display = "none";
        const parentForm = error.closest(".input-form");
        if (parentForm) parentForm.classList.remove("has-error");
      });
    }

    // Gắn sự kiện click cho nút "Xóa trống"
    const clearButton = document.querySelector('button[name="clear"]');
    if (clearButton) {
      clearButton.addEventListener("click", clearForm);
    }
  }
});
