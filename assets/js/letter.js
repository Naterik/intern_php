document.addEventListener("DOMContentLoaded", function () {
  flatpickr("#datetimepicker", {
    dateFormat: "Y-m-d",
    minDate: "1900-01-01",
    maxDate: "2100-12-31",
  });

  flatpickr("#datetimepicker2", {
    dateFormat: "Y-m-d",
    minDate: "1900-01-01",
    maxDate: "2100-12-31",
  });

  const requiredStars = document.querySelectorAll(".required-star");
  requiredStars.forEach((star) => {
    star.style.display = "none"; // Ẩn sao mặc định
  });

  document
    .getElementById("file-input")
    .addEventListener("change", function (event) {
      const fileInput = event.target;
      const fileDisplay = document.querySelector(".file-display");
      const fileNameSpan = document.querySelector(".file-name");
      const fileLabel = document.querySelector(".custom-file-label");

      if (fileInput.files.length > 0) {
        fileNameSpan.textContent = fileInput.files[0].name;
        fileDisplay.style.display = "flex";
        fileLabel.style.display = "none";
      } else {
        fileDisplay.style.display = "none";
        fileLabel.style.display = "flex";
      }
    });

  document.querySelector(".remove-file").addEventListener("click", function () {
    const fileInput = document.getElementById("file-input");
    const fileDisplay = document.querySelector(".file-display");
    const fileLabel = document.querySelector(".custom-file-label");

    fileInput.value = "";
    fileDisplay.style.display = "none";
    fileLabel.style.display = "flex";
  });

  // Sửa từ "error-message" thành "error" để khớp với HTML
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

  document
    .querySelector('button[name="clear"]')
    .addEventListener("click", function () {
      const form = document.getElementById("upload-form");
      form.reset();

      const fileDisplay = document.querySelector(".file-display");
      const fileLabel = document.querySelector(".custom-file-label");
      fileDisplay.style.display = "none";
      fileLabel.style.display = "flex";

      errors.forEach((error, index) => {
        error.textContent = "";
        requiredStars[index].style.display = "none";
        error.closest(".input-form").classList.remove("has-error");
      });
    });
});
