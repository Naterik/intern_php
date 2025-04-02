<?php

class BaseModel
{
  protected $table;
  protected $pdo;

  public function __construct()
  {
    $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8', DB_HOST, DB_PORT, DB_NAME);
    try {
      $this->pdo = new PDO($dsn, DB_USERNAME, DB_PASSWORD, DB_OPTIONS);
      $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
      die("Kết nối cơ sở dữ liệu thất bại: " . $e->getMessage() . ". Vui lòng thử lại sau.");
    }
  }
  public function __destruct()
  {
    $this->pdo = null;
  }
  public function beginTransaction()
  {
    if (!$this->pdo->inTransaction()) {
      $this->pdo->beginTransaction();
    }
  }

  public function commit()
  {
    if ($this->pdo->inTransaction()) {
      $this->pdo->commit();
    }
  }

  public function rollBack()
  {
    if ($this->pdo->inTransaction()) {
      $this->pdo->rollBack();
    }
  }
  public function search($searchUsername = null, $searchUserId = null)
  {

    try {
      if ($searchUsername === null && $searchUserId === null) {
        return [];
      }
      $stmt = $this->pdo->prepare('CALL GetSearch(:p_searchUsername, :p_searchUserId)');
      $stmt->bindParam(':p_searchUsername', $searchUsername, PDO::PARAM_STR);
      $stmt->bindParam(':p_searchUserId', $searchUserId, PDO::PARAM_INT);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      throw new Exception("Lỗi khi tìm kiếm: " . $e->getMessage());
    }
  }

  public function create(array $data)
  {
    try {
      $columns = implode(', ', array_keys($data));
      $values = implode(', ', array_map(function ($value) {
        return $this->pdo->quote($value);
      }, array_values($data)));

      $stmt = $this->pdo->prepare('CALL GetCreate(:p_tableName, :p_columns, :p_values)');
      $stmt->bindParam(':p_tableName', $this->table, PDO::PARAM_STR);
      $stmt->bindParam(':p_columns', $columns, PDO::PARAM_STR);
      $stmt->bindParam(':p_values', $values, PDO::PARAM_STR);
      $stmt->execute();

      return true;
    } catch (PDOException $e) {
      throw new Exception("Lỗi khi tạo bản ghi: " . $e->getMessage());
    }
  }

  public function update(array $setData, $whereClause)
  {
    $this->beginTransaction();

    try {
      // Tạo SET clause an toàn
      $setParts = [];
      foreach ($setData as $key => $value) {
        // Xử lý giá trị NULL
        if ($value === null) {
          $setParts[] = "`$key` = NULL";
        } else {
          $setParts[] = "`$key` = " . $this->pdo->quote($value);
        }
      }
      $setClause = implode(', ', $setParts);

      // Gọi stored procedure
      $stmt = $this->pdo->prepare('CALL GenericUpdate(:p_tableName, :p_setClause, :p_whereClause)');
      $stmt->execute([
        ':p_tableName' => $this->table,
        ':p_setClause' => $setClause,
        ':p_whereClause' => $whereClause
      ]);

      $this->commit();
      return true;
    } catch (PDOException $e) {
      $this->rollBack();
      throw new Exception("Update failed: " . $e->getMessage());
    }
  }


  public function paginate($limitVal, $offsetVal)
  {
    try {
      $stmt = $this->pdo->prepare('CALL GetPaginate(:p_tableName, :p_limitVal, :p_offsetVal)');
      $stmt->bindParam(':p_tableName', $this->table, PDO::PARAM_STR);
      $stmt->bindParam(':p_limitVal', $limitVal, PDO::PARAM_INT);
      $stmt->bindParam(':p_offsetVal', $offsetVal, PDO::PARAM_INT);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      throw new Exception("Lỗi khi phân trang: " . $e->getMessage());
    }
  }
}
