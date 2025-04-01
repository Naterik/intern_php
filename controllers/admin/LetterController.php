<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

class LetterController
{
  private $letterModel;
  private $userModel;

  public function __construct()
  {
    $this->letterModel = new Letter();
    $this->userModel = new User();
  }

  public function index()
  {
    $searchTerm = $_GET['search'] ?? '';
    $page = (int)($_GET['page'] ?? 1);
    $limit = 10;
    $offset = ($page - 1) * $limit;
    $allLetters = $this->letterModel->getAllLetters();
    if ($searchTerm) {
      $filteredLetters = array_filter($allLetters, function ($letter) use ($searchTerm) {
        return stripos($letter['userId'], $searchTerm) !== false ||
          stripos($letter['typesOfApplication'], $searchTerm) !== false ||
          stripos($letter['content'], $searchTerm) !== false;
      });
    } else {
      $filteredLetters = $allLetters;
    }
    $totalLetters = count($filteredLetters);
    $totalPages = ceil($totalLetters / $limit);
    $data = array_slice($filteredLetters, $offset, $limit);
    $pagination = ($totalLetters > $limit);
    require_once PATH_VIEW_ADMIN . 'letters/index.php';
  }

  public function create()
  {
    $inputData = [];
    $errors = [];
    if (!isset($_SESSION['userId']) || empty($_SESSION['userId'])) {
      $_SESSION['error'] = "Vui lòng đăng nhập để tạo đơn.";
      header("Location: " . BASE_URL_ADMIN . "?action=login");
      exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      if (isset($_POST['next'])) {
        $inputData = $_POST;
        $errors = $this->validateLetterData($inputData);

        if (empty($errors)) {
          if (isset($_FILES['attach']) && $_FILES['attach']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/';
            if (!file_exists($uploadDir)) {
              mkdir($uploadDir, 0777, true);
            }
            $fileName = basename($_FILES['attach']['name']);
            $uploadFile = $uploadDir . $fileName;
            if (move_uploaded_file($_FILES['attach']['tmp_name'], $uploadFile)) {
              $inputData['attachment'] = $uploadFile;
            } else {
              $errors['attach'] = 'Có lỗi xảy ra khi upload file';
            }
          } else {
            $errors['attach'] = 'Vui lòng chọn một file để upload';
          }

          if (empty($errors)) {
            $inputData['userId'] = $_SESSION['userId'];
            $_SESSION['letterData'] = $inputData;
            require_once PATH_VIEW_ADMIN . 'letters/create.confirm.php';
            exit();
          }
        }
      } elseif (isset($_POST['clear'])) {
        $inputData = [];
        $errors = [];
      }
    }

    require_once PATH_VIEW_ADMIN . 'letters/create.php';
  }

  public function save()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm']) && $_POST['confirm'] === 'true') {
      if (isset($_SESSION['letterData'])) {
        $letterData = $_SESSION['letterData'];
        $data = [
          'userId' => $letterData['userId'],
          'approver' => $letterData['approver'],
          'title' => $letterData['title'],
          'content' => $letterData['content'] ?? '',
          'typesOfApplication' => $letterData['typesOfApplication'],
          'startDate' => $letterData['startDate'],
          'endDate' => $letterData['endDate'],
          'attachment' => $letterData['attachment']
        ];

        try {
          $this->letterModel->create($data);
          unset($_SESSION['letterData']);
          header("Location: " . BASE_URL_ADMIN . "?action=letters-index");
          exit();
        } catch (Exception $e) {
          error_log($e->getMessage());
          $_SESSION['error'] = "Lỗi hệ thống, vui lòng thử lại sau.";
          header("Location: " . BASE_URL_ADMIN . "?action=letters-create");
          exit();
        }
      } else {
        $_SESSION['error'] = "Dữ liệu không hợp lệ.";
        header("Location: " . BASE_URL_ADMIN . "?action=letters-create");
        exit();
      }
    }
  }

  public function approve()
  {
    try {
      $letterId = $_POST['letterId'] ?? $_GET['letterId'] ?? null;
      if (!$letterId) {
        throw new Exception("Không tìm thấy ID đơn.");
      }

      $letter = $this->letterModel->getLetterById($letterId);
      if (!$letter) {
        throw new Exception("Không tìm thấy đơn.");
      }

      if (!isset($_SESSION['userId']) || !isset($_SESSION['categoryUser']) || strtolower(trim($_SESSION['categoryUser'])) !== 'admin') {
        throw new Exception('Chỉ admin mới có quyền duyệt đơn!');
      }

      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $newStatus = $_POST['status'] ?? null;
        if (!in_array($newStatus, ['đã duyệt', 'đã hủy'])) {
          throw new Exception('Trạng thái không hợp lệ!');
        }

        // Cập nhật trạng thái đơn
        $this->letterModel->updateLetterStatus($letterId, $newStatus, $_SESSION['userId']);
        $message = "Đơn đã được " . $newStatus . " thành công!";
        $reason = null;
        if ($newStatus === 'đã hủy' && isset($_POST['reason'])) {
          $reason = htmlspecialchars($_POST['reason']);
          $message .= " Lý do: " . $reason;
        }

        // Lấy email của người dùng (người tạo đơn)
        $userEmail = $_SESSION['email'];
        if (empty($userEmail)) {
          throw new Exception('Không tìm thấy email của người dùng!');
        }

        // Lấy thông tin người duyệt (approver) bằng userId
        $approver = $this->userModel->getAdminUserById($letter['approver']);
        if (!$approver) {
          throw new Exception('Không tìm thấy thông tin người duyệt!');
        }

        // Gửi email đến người dùng
        $this->sendApprovalEmail($userEmail, $letter, $newStatus, $approver, $reason);

        $_SESSION['success'] = $message;
        header("Location: " . BASE_URL_ADMIN . "?action=letters-index");
        exit();
      }

      // Hiển thị form approve.php
      require_once PATH_VIEW_ADMIN . 'letters/approve.php';
    } catch (Exception $e) {
      $_SESSION['error'] = $e->getMessage();
      header("Location: " . BASE_URL_ADMIN . "?action=letters-index");
      exit();
    }
  }

  private function sendApprovalEmail($to, $letter, $status, $approver, $reason = null)
  {
    $mail = new PHPMailer(true);
    try {
      // Cấu hình SMTP
      $mail->isSMTP();
      $mail->Host = 'smtp.gmail.com';
      $mail->SMTPAuth = true;
      $mail->Username = 'khuonglol12@gmail.com';
      $mail->Password = 'stfl bwqx fajo dcxm';
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
      $mail->Port = 587;

      // Cấu hình email
      $mail->setFrom('khuonglol12@gmail.com', 'Admin');
      $mail->addAddress($to);
      $mail->Subject = "About your letter";
      $mail->isHTML(true);

      // Tạo nội dung email
      $body = "<p>Đơn của bạn với tiêu đề: <strong>" . htmlspecialchars($letter['title']) . "</strong> " . htmlspecialchars($status) . ".</p>"
        . "<p>Người duyệt: <strong>" . htmlspecialchars($approver['fullname']) . "</strong> (" . htmlspecialchars($approver['email']) . ")</p>"
        . "<p>Loại đơn: <strong>" . htmlspecialchars($letter['typesOfApplication']) . "</strong></p>"
        . "<p>Mô tả: " . htmlspecialchars($letter['content']) . "</p>";

      if ($status === 'đã hủy' && $reason) {
        $body .= "<p>Lý do hủy: " . htmlspecialchars($reason) . "</p>";
      }

      $mail->Body = $body;

      // Gửi email
      $mail->send();
    } catch (Exception $e) {
      error_log("Lỗi gửi email: " . $mail->ErrorInfo);
    }
  }

  public function validateLetterData(array $data)
  {
    $errors = [];

    if (empty(trim($data['title']))) {
      $errors['title'] = '※Tiêu đề không được để trống';
    }

    if (empty($data['approver'])) {
      $errors['approver'] = '※Vui lòng chọn người duyệt';
    }

    if (empty($data['typesOfApplication'])) {
      $errors['typesOfApplication'] = '※Vui lòng chọn loại đơn';
    }

    if (empty($data['startDate'])) {
      $errors['startDate'] = '※Ngày bắt đầu không được để trống';
    }

    if (empty($data['endDate'])) {
      $errors['endDate'] = '※Ngày kết thúc không được để trống';
    }

    if (isset($_POST['next']) && (!isset($_FILES['attach']) || $_FILES['attach']['error'] !== UPLOAD_ERR_OK)) {
      $errors['attachment'] = '※Vui lòng chọn file đính kèm';
    } elseif (isset($_FILES['attach']) && $_FILES['attach']['error'] === UPLOAD_ERR_OK) {
      $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml'];
      $fileType = mime_content_type($_FILES['attach']['tmp_name']);
      if (!in_array($fileType, $allowedTypes)) {
        $errors['attachment'] = '※Chỉ cho phép upload các file hình ảnh được hỗ trợ';
      }
    }

    return $errors;
  }
}
