document.addEventListener("DOMContentLoaded", function () {
  // Khởi tạo Flatpickr cho trường datetime
  flatpickr("#datetimepicker", {
    dateFormat: "Y-m-d",
    minDate: "1900-01-01",
    maxDate: "2100-12-31",
    enableTime: false,
  });

  // Ẩn các required-star ban đầu
  const requiredStars = document.querySelectorAll("span[style*='color: red']");
  requiredStars.forEach((star) => {
    star.style.display = "none";
  });

  // Hiển thị required-star và trạng thái has-error nếu có lỗi
  const errors = document.querySelectorAll(".error");
  errors.forEach((error, index) => {
    const errorText = error.textContent.trim();
    const correspondingStar = requiredStars[index];
    const parentForm = error.closest(".input-form");

    if (errorText !== "") {
      correspondingStar.style.display = "inline";
      parentForm.classList.add("has-error"); // Thêm viền đỏ khi có lỗi
    } else {
      correspondingStar.style.display = "none";
      parentForm.classList.remove("has-error");
    }
  });

  // Gắn sự kiện click cho nút "Xóa trống"
  document
    .querySelector('button[name="clear"]')
    .addEventListener("click", function () {
      const form = document.getElementById("user-form");
      if (!form) return;

      // Reset form
      form.reset();

      // Xóa thông báo lỗi, required-star và trạng thái has-error
      errors.forEach((error, index) => {
        error.textContent = "";
        requiredStars[index].style.display = "none";
        error.closest(".input-form").classList.remove("has-error");
      });
    });
});
