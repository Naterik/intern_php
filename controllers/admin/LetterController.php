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

    // Lấy dữ liệu từ cả hai bảng
    $allLetters = $this->letterModel->getAllLettersWithUsernames();

    if ($searchTerm) {
      $filteredLetters = array_filter($allLetters, function ($letter) use ($searchTerm) {
        return stripos($letter['username'], $searchTerm) !== false ||
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
    $inputData = $_SESSION['letterData'] ?? [];
    $errors = [];
    if (!isset($_SESSION['userId']) || empty($_SESSION['userId'])) {
      $_SESSION['error'] = "Vui lòng đăng nhập để tạo đơn.";
      header("Location: " . BASE_URL_ADMIN . "?action=login");
      exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      if (isset($_POST['next'])) {
        $inputData = array_merge($inputData, $_POST);
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
          }

          // Kiểm tra nếu không có file mới và không có attachment cũ
          if (!isset($inputData['attachment']) || empty($inputData['attachment'])) {
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
        unset($_SESSION['letterData']);
      }
    }

    require_once PATH_VIEW_ADMIN . 'letters/create.php';
  }

  private function validateLetterData($data)
  {
    $errors = [];

    if (empty($data['title'])) {
      $errors['title'] = "Tiêu đề không được để trống.";
    }

    if (empty($data['approver'])) {
      $errors['approver'] = "Người duyệt không được để trống.";
    }

    if (empty($data['typesOfApplication'])) {
      $errors['typesOfApplication'] = "Loại đơn không được để trống.";
    }

    if (empty($data['startDate'])) {
      $errors['startDate'] = "Ngày bắt đầu không được để trống.";
    }

    if (empty($data['endDate'])) {
      $errors['endDate'] = "Ngày kết thúc không được để trống.";
    }

    return $errors;
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
    $letterId = $_POST['letterId'] ?? $_GET['letterId'] ?? null;
    if (!$letterId) {
      $errorMessage = "Không tìm thấy ID đơn.";
      require_once PATH_VIEW_ADMIN . 'letters/approve.php';
      return;
    }

    $letter = $this->letterModel->getLetterById($letterId);
    if (!$letter) {
      $errorMessage = "Không tìm thấy đơn.";
      require_once PATH_VIEW_ADMIN . 'letters/approve.php';
      return;
    }

    if (!isset($_SESSION['userId']) || !isset($_SESSION['categoryUser'])) {
      $errorMessage = "Vui lòng đăng nhập để duyệt đơn.";
      require_once PATH_VIEW_ADMIN . 'letters/approve.php';
      return;
    }

    $categoryUser = strtolower(trim($_SESSION['categoryUser']));
    if ($categoryUser !== 'admin' && $categoryUser !== 'manager') {
      $errorMessage = "Chỉ admin và manager mới có quyền duyệt đơn!";
      require_once PATH_VIEW_ADMIN . 'letters/approve.php';
      return;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $newStatus = $_POST['status'] ?? null;
      if (!in_array($newStatus, ['đã duyệt', 'đã hủy'])) {
        $errorMessage = "Trạng thái không hợp lệ!";
        require_once PATH_VIEW_ADMIN . 'letters/approve.php';
        return;
      }

      // Cập nhật trạng thái đơn và lấy kết quả
      $result = $this->letterModel->updateLetterStatus($letterId, $newStatus, $_SESSION['userId']);

      if ($result['success']) {
        $message = "Đơn đã được " . $newStatus . " thành công!";
        $reason = null;
        if ($newStatus === 'đã hủy' && isset($_POST['reason'])) {
          $reason = htmlspecialchars($_POST['reason']);
          $message .= " Lý do: " . $reason;
        }

        $creator = $this->userModel->getApproverById($letter['userId']);
        if (!$creator || empty($creator['email'])) {
          $errorMessage = "Không tìm thấy email của người tạo đơn!";
          require_once PATH_VIEW_ADMIN . 'letters/approve.php';
          return;
        }
        $userEmail = $creator['email'];

        // Lấy thông tin người duyệt (approver)
        $approver = $this->userModel->getApproverById($letter['approver']);
        if (!$approver) {
          $errorMessage = "Không tìm thấy thông tin người duyệt!";
          require_once PATH_VIEW_ADMIN . 'letters/approve.php';
          return;
        }

        // Gửi email thông báo
        $this->sendApprovalEmail($userEmail, $letter, $newStatus, $approver, $reason);

        $_SESSION['success'] = $message;
        header("Location: " . BASE_URL_ADMIN . "?action=letters-index");
        exit();
      } else {
        $errorMessage = $result['message'];
      }
    }

    // Hiển thị form approve.php với thông báo lỗi nếu có
    require_once PATH_VIEW_ADMIN . 'letters/approve.php';
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
}
