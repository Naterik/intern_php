<?php
require_once 'BaseModel.php';

class Letter extends BaseModel
{

  protected $table = 'letters';


  // Lấy danh sách letter theo giới hạn, sử dụng stored procedure GetAllLetters
  public function getAllLetters($limit = 30)
  {
    try {
      $stmt = $this->pdo->prepare("CALL GetAllLetters(:p_limit)");
      $stmt->bindParam(':p_limit', $limit, PDO::PARAM_INT);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      throw new Exception("Lỗi khi lấy đơn: " . $e->getMessage());
    }
  }

  // Tìm kiếm letters theo từ khóa và userId (nếu có)
  public function searchLetters($searchTerm, $userId = null)
  {
    try {
      $stmt = $this->pdo->prepare("CALL SearchLetters(:p_searchText, :p_searchUserId)");
      $stmt->bindValue(':p_searchText', $searchTerm, PDO::PARAM_STR);
      $stmt->bindValue(':p_searchUserId', $userId, PDO::PARAM_INT);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      throw new Exception("Lỗi khi tìm kiếm letters: " . $e->getMessage());
    }
  }
}
