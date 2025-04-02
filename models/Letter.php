<?php
require_once 'BaseModel.php';

class Letter extends BaseModel
{
  protected $table = 'letters';

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

  public function countSearchResults($searchTerm, $userId = null)
  {
    try {
      $stmt = $this->pdo->prepare('CALL sp_CountSearchResults(:p_searchText, :p_searchUserId)');
      $stmt->bindValue(':p_searchText', $searchTerm, PDO::PARAM_STR);
      $stmt->bindValue(':p_searchUserId', $userId, PDO::PARAM_INT);
      $stmt->execute();
      return $stmt->fetchColumn();
    } catch (PDOException $e) {
      throw new Exception("Lỗi khi đếm kết quả tìm kiếm: " . $e->getMessage());
    }
  }
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
  public function create(array $data)
  {
    try {
      $this->beginTransaction();

      $stmt = $this->pdo->prepare('CALL CreateNewLetter(:p_userId, :p_approver, :p_title, :p_content, :p_typesOfApplication, :p_startDate, :p_endDate, :p_attachment)');
      $stmt->bindParam(':p_userId', $data['userId'], PDO::PARAM_INT);
      $stmt->bindParam(':p_approver', $data['approver'], PDO::PARAM_INT);
      $stmt->bindParam(':p_title', $data['title'], PDO::PARAM_STR);
      $stmt->bindParam(':p_content', $data['content'], PDO::PARAM_STR);
      $stmt->bindParam(':p_typesOfApplication', $data['typesOfApplication'], PDO::PARAM_STR);
      $stmt->bindParam(':p_startDate', $data['startDate'], PDO::PARAM_STR);
      $stmt->bindParam(':p_endDate', $data['endDate'], PDO::PARAM_STR);
      $stmt->bindParam(':p_attachment', $data['attachment'], PDO::PARAM_STR);
      $stmt->execute();

      $this->commit();
      return true;
    } catch (PDOException $e) {
      $this->rollBack();
      throw new Exception("Lỗi khi tạo đơn mới: " . $e->getMessage());
    }
  }
  public function getLetterById($letterId)
  {
    $stmt = $this->pdo->prepare("CALL GetLetterById(:letterId)");
    $stmt->bindParam(':letterId', $letterId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function updateLetterStatus($letterId, $newStatus, $currentUserId)
  {
    try {
      $stmt = $this->pdo->prepare("CALL UpdateLetterStatus(:letterId, :newStatus, :currentUserId)");
      $stmt->execute([
        'letterId' => $letterId,
        'newStatus' => $newStatus,
        'currentUserId' => $currentUserId
      ]);
      return ['success' => true, 'message' => "Cập nhật trạng thái thành công!"];
    } catch (PDOException $e) {
      return ['success' => false, 'message' => "Lỗi khi cập nhật trạng thái: " . $e->getMessage()];
    }
  }
  public function getAllLettersWithUsernames()
  {
    $query = "SELECT l.*, u.username 
              FROM letters l 
              JOIN users u ON l.userId = u.userId";

    $stmt = $this->pdo->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}
