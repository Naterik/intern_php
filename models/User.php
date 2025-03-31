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
      return $stmt->fetch(PDO::FETCH_ASSOC); // Trả về mảng kết quả
    } catch (PDOException $e) {
      throw new Exception("Lỗi: " . $e->getMessage());
    }
  }
  public function deleteByUserId($deleteUserId)
  {
    try {
      $stmt = $this->pdo->prepare('CALL GetBulkDeleteByUserId(:p_deleteUserId)');
      $stmt->bindParam(':p_deleteUserId', $deleteUserId, PDO::PARAM_INT);
      $stmt->execute();
      return true;
    } catch (PDOException $e) {
      throw new Exception("Lỗi khi xóa bản ghi: " . $e->getMessage());
    }
  }
  public function getAdminUsers()
  {
    try {
      $stmt = $this->pdo->prepare('CALL GetAdminUsers()');
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      throw new Exception("Lỗi khi lấy danh sách admin: " . $e->getMessage());
    }
  }
  public function getAdminUserById($userId)
  {
    try {
      $stmt = $this->pdo->prepare('CALL GetAdminUserById(:userId)');
      $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
      $stmt->execute();
      return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      throw new Exception("Lỗi khi lấy thông tin admin: " . $e->getMessage());
    }
  }
}
