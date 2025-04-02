<?php
require_once 'BaseModel.php';

class User extends BaseModel
{
  protected $table = 'users';

  public function checkLogin($username, $password)
  {
    $sql = "CALL CheckUserLogin(:username, :password)";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(':username', $username);
    $stmt->bindValue(':password', $password);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }
  public function getUserById(int $userId)
  {
    try {
      $stmt = $this->pdo->prepare("CALL GetUserById(:userId)");
      $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
      $stmt->execute();
      return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      throw new Exception("Lỗi: " . $e->getMessage());
    }
  }
  public function deleteByUserId($deleteUserId)
  {
    try {
      $stmt = $this->pdo->prepare('CALL GetBulkDeleteByUserId(:p_deleteUserId, @rowsAffected)');
      $stmt->bindParam(':p_deleteUserId', $deleteUserId, PDO::PARAM_INT);
      $stmt->execute();

      // Lấy số bản ghi bị xóa từ biến OUT
      $stmt = $this->pdo->query('SELECT @rowsAffected AS rowsAffected');
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      $rowsAffected = (int)($result['rowsAffected'] ?? 0);

      return $rowsAffected > 0; // Trả về true nếu có ít nhất 1 bản ghi bị xóa
    } catch (PDOException $e) {
      throw new Exception("Lỗi khi xóa bản ghi: " . $e->getMessage());
    }
  }
  public function deleteMultipleUsers($userIds)
  {
    try {
      $userIdsStr = implode(',', $userIds);
      $stmt = $this->pdo->prepare('CALL GetBulkDeleteMultipleUsers(:p_userIds)');
      $stmt->bindParam(':p_userIds', $userIdsStr, PDO::PARAM_STR);
      $stmt->execute();
      return true;
    } catch (PDOException $e) {
      throw new Exception("Lỗi khi xóa nhiều bản ghi: " . $e->getMessage());
    }
  }
  public function getApproveUsers()
  {
    try {
      $stmt = $this->pdo->prepare('CALL GetApproveUsers()');
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      throw new Exception("Lỗi khi lấy danh sách admin: " . $e->getMessage());
    }
  }
  public function getApproverById($userId)
  {
    try {
      $stmt = $this->pdo->prepare('CALL GetApproverById(:userId)');
      $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
      $stmt->execute();
      return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      throw new Exception("Lỗi khi lấy thông tin người duyệt: " . $e->getMessage());
    }
  }
  public function getAllUsers()
  {
    try {
      $stmt = $this->pdo->prepare('SELECT * FROM users');
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      throw new Exception("Lỗi khi lấy danh sách người dùng: " . $e->getMessage());
    }
  }
  public function checkUsernameExists($username)
  {
    try {
      $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM users WHERE username = :username');
      $stmt->bindParam(':username', $username, PDO::PARAM_STR);
      $stmt->execute();
      return $stmt->fetchColumn() > 0;
    } catch (PDOException $e) {
      throw new Exception("Lỗi khi kiểm tra username: " . $e->getMessage());
    }
  }
}
