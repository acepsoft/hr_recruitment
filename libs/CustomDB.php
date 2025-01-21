<?php
class CustomDB {
    private $user;
    private $password;
    private $dbname;
    private $host = 'localhost:3306';
    private $pdo;
    public $connected = false;

    public function __construct($config = []) {
        $this->user = $config['db_user'] ?? 'your_db_user';
        $this->password = $config['db_password'] ?? 'your_db_password';
        $this->dbname = $config['db_name'] ?? 'your_db_name';
        $this->host = $config['db_host'] ?? 'localhost:3306';

        $this->connect();
    }

    private function connect() {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset=utf8";
            $this->pdo = new PDO($dsn, $this->user, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connected = true;
        } catch (PDOException $e) {
            $errorMessage = sprintf("[%s] [%s] %s\n", date('Y-m-d H:i:s'), $_SERVER['HTTP_HOST'], $e->getMessage());
            error_log($errorMessage, 3, 'errors_to_check.log');
        }
    }

    public function __destruct() {
        $this->disconnect();
    }

    public function getLastInsertId() {
        return $this->pdo->lastInsertId();
    }

    public function selectDatabase($dbname) {
        $this->dbname = $dbname;
        $this->pdo->exec("USE $dbname");
    }

    public function disconnect() {
        $this->pdo = null;
    }

    public function executeQuery($query, $params = []) {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function recordExists($table, $id) {
        $query = "SELECT id FROM $table WHERE id = :id";
        $result = $this->executeQuery($query, [':id' => $id]);
        return count($result) === 1;
    }

    public function setCharsetToLatin1() {
        $this->pdo->exec("SET NAMES 'latin1'");
    }

    public function setCharsetToUtf8() {
        $this->pdo->exec("SET NAMES 'utf8'");
    }
}
?>