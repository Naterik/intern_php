document.addEventListener("DOMContentLoaded", function () {
  flatpickr("#datetimepicker", {
    dateFormat: "Y-m-d",
    minDate: "1900-01-01",
    maxDate: "2100-12-31",
    enableTime: false,
  });

  const fields = [
    "name",
    "email",
    "birthdate",
    "user_type",
    "department",
    "status",
  ];

  const errors = window.errors || {};

  if (Object.keys(errors).length > 0) {
    for (const field of fields) {
      if (errors[field]) {
        document.getElementById(field)?.focus();
        break;
      }
    }
  }

  fields.forEach((field) => {
    const input = document.getElementById(field);
    if (input) {
      input.addEventListener("input", () => clearError(field));

      if (input.tagName === "SELECT") {
        input.addEventListener("change", () => clearError(field));
      }
    }
  });
});

// Hàm xóa thông báo lỗi của 1 trường
function clearError(field) {
  const errorSpan = document.querySelector(`.input-form#${field} + .error`);
  if (errorSpan) errorSpan.remove();

  const inputForm = document.getElementById(field)?.closest(".input-form");
  if (inputForm) inputForm.classList.remove("has-error");
}

// Hàm XÓA TRỐNG TOÀN BỘ form
function clearForm() {
  const form = document.getElementById("user-form");
  if (!form) return;

  // Clear các input text/email
  form
    .querySelectorAll("input[type='text'], input[type='email']")
    .forEach((input) => {
      // Không clear trường readonly (username)
      if (!input.hasAttribute("readonly")) {
        input.value = "";
      }
    });

  // Clear select box
  form.querySelectorAll("select").forEach((select) => {
    select.value = "";
  });

  // Clear datetimepicker
  const datePicker = flatpickr.getInstance(
    document.getElementById("datetimepicker")
  );
  if (datePicker) {
    datePicker.clear();
  } else {
    document.getElementById("datetimepicker").value = "";
  }

  // Xóa tất cả thông báo lỗi
  document.querySelectorAll(".error").forEach((error) => error.remove());
  document
    .querySelectorAll(".has-error")
    .forEach((element) => element.classList.remove("has-error"));
}

// Đưa hàm clearForm ra phạm vi toàn cục
window.clearForm = clearForm;
