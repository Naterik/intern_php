<?php


class LetterController
{
  private $letterModel;

  public function __construct()
  {
    // Khởi tạo model Letter (đảm bảo đã require file Letter.php)
    $this->letterModel = new Letter();
  }

  public function index()
  {
    // Lấy giá trị tìm kiếm và phân trang từ GET
    $searchTerm = $_GET['search'] ?? '';
    $page = (int)($_GET['page'] ?? 1);
    $limit = 10;
    $offset = ($page - 1) * $limit;

    // Lấy toàn bộ kết quả theo search để tính tổng số letter (có thể tối ưu bằng truy vấn COUNT riêng)
    $searchResults = $this->letterModel->searchLetters($searchTerm, null);
    $totalLetters = count($searchResults);
    $totalPages = ceil($totalLetters / $limit);

    // Lấy danh sách letters theo phân trang
    $letters = $this->letterModel->paginate($limit, $offset);

    // Nếu có tìm kiếm, lọc lại kết quả (trên mảng kết quả trả về từ paginate)
    if ($searchTerm) {
      $letters = array_filter($letters, function ($letter) use ($searchTerm) {
        return (stripos($letter['title'], $searchTerm) !== false) ||
          (stripos($letter['content'], $searchTerm) !== false);
      });
      $letters = array_slice($letters, 0, $limit);
    }

    // Truyền dữ liệu sang view
    require_once PATH_VIEW_ADMIN . 'letters/index.php';
  }
}
