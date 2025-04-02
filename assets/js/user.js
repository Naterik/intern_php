document.addEventListener("DOMContentLoaded", function () {
  // Khởi tạo Flatpickr cho trường ngày sinh
  const datetimePicker = document.getElementById("datetimepicker");
  if (datetimePicker) {
    flatpickr("#datetimepicker", {
      dateFormat: "Y-m-d",
      minDate: "1900-01-01",
      maxDate: "2100-12-31",
      enableTime: false,
      defaultDate: datetimePicker.value || null,
    });
  }

  const form = document.getElementById("user-form");
  const requiredStars = document.querySelectorAll(".required-mark");
  const errors = document.querySelectorAll(".error");

  if (form && requiredStars.length > 0) {
    // Ẩn các ngôi sao yêu cầu ban đầu
    requiredStars.forEach((star) => {
      star.style.display = "none";
    });

    // Xử lý lỗi từ server
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

    // Hàm xử lý nút "Xóa trống"
    function clearForm() {
      // Lấy tất cả các input và select (bao gồm username, không loại trừ disabled nữa)
      const allInputs = form.querySelectorAll(
        "input:not([type='hidden']), select"
      );

      allInputs.forEach((input) => {
        if (input.type === "text" || input.type === "password") {
          input.value = ""; // Xóa trống các trường text/password
        } else if (input.tagName === "SELECT") {
          input.selectedIndex = 0; // Đặt select về giá trị đầu tiên
        }
      });

      // Xóa giá trị của Flatpickr
      if (datetimePicker) {
        flatpickr("#datetimepicker").clear();
      }

      // Xóa các thông báo lỗi và trạng thái lỗi
      errors.forEach((error) => {
        error.textContent = "";
        const correspondingStar = error
          .closest(".form")
          .querySelector(".required-mark");
        if (correspondingStar) correspondingStar.style.display = "none";
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
