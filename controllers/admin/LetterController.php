<?php
class LetterController
{
  private $letterModel;

  public function __construct()
  {
    $this->letterModel = new Letter();
  }

  public function index()
  {
    // Lấy giá trị tìm kiếm và phân trang từ GET
    $searchTerm = $_GET['search'] ?? '';
    $page = (int)($_GET['page'] ?? 1);
    $limit = 10;
    $offset = ($page - 1) * $limit;
    $searchResults = $this->letterModel->searchLetters($searchTerm, null);
    $totalLetters = count($searchResults);
    $totalPages = ceil($totalLetters / $limit);

    $letters = $this->letterModel->paginate($limit, $offset);
    if ($searchTerm) {
      $letters = array_filter($letters, function ($letter) use ($searchTerm) {
        return (stripos($letter['title'], $searchTerm) !== false) ||
          (stripos($letter['content'], $searchTerm) !== false);
      });
      $letters = array_slice($letters, 0, $limit);
    }
    require_once PATH_VIEW_ADMIN . 'letters/index.php';
  }

  public function create()
  {
    $inputData = [];
    $errors = [];

    // Kiểm tra đăng nhập trước khi hiển thị form
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
          // Xử lý file upload
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
            // Đảm bảo userId được lưu vào session
            $inputData['userId'] = $_SESSION['userId']; // Gán trực tiếp từ session
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
          'userId' => $letterData['userId'], // Lấy từ session
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
          $_SESSION['success'] = "Đơn mới đã được tạo thành công.";
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
      // Kiểm tra quyền
      if (!isset($_SESSION['userId']) || !isset($_SESSION['categoryUser']) || strtolower($_SESSION['categoryUser']) !== 'admin') {
        throw new Exception('Chỉ admin mới có quyền duyệt đơn!');
      }

      // Lấy letterId
      $letterId = $_REQUEST['letterId'] ?? null;
      if (empty($letterId)) {
        throw new Exception('Không tìm thấy đơn để duyệt!');
      }

      // Lấy thông tin đơn
      $letter = $this->letterModel->getLetterById($letterId);
      if (!$letter) {
        throw new Exception('Đơn không tồn tại!');
      }

      // Xử lý duyệt hoặc hủy (POST)
      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $newStatus = $_POST['status'] ?? null;
        if (!in_array($newStatus, ['đã duyệt', 'đã hủy'])) {
          throw new Exception('Trạng thái không hợp lệ!');
        }

        // Gọi stored procedure
        $this->letterModel->updateLetterStatus($letterId, $newStatus, $_SESSION['userId']);

        // Thông báo thành công
        $message = "Đơn đã được " . $newStatus . " thành công!";
        if ($newStatus === 'đã hủy' && isset($_POST['reason'])) {
          $message .= " Lý do: " . htmlspecialchars($_POST['reason']);
        }
        $_SESSION['success'] = $message;

        header("Location: " . BASE_URL_ADMIN . "?action=letters-approve&letterId=" . $letterId);
        exit();
      }

      // Hiển thị form nếu GET
      require_once PATH_VIEW_ADMIN . 'letters/approve.php';
    } catch (Exception $e) {
      $_SESSION['error'] = $e->getMessage();
      header("Location: " . BASE_URL_ADMIN . "?action=letters-index");
      exit();
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
