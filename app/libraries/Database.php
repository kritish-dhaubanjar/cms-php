<?php
class Database
{
    private $dbh;
    private $errors;
    private $stmt;

    public function __construct()
    {
        $dsn = "mysql:host=" . HOST . ";dbname=" . DATABASE;

        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );

        try {
            $this->dbh = new PDO($dsn, USER, PASSWORD, $options);
        } catch (PDOException $e) {
            $this->errors = $e;
            die($e);
        }
    }

    public function query($sql)
    {
        $this->stmt = $this->dbh->prepare($sql);
    }

    public function execute()
    {
        $this->stmt->execute();
    }

    public function fetch()
    {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_OBJ);
    }

    public function fetchAll()
    {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function lastInsertId()
    {
        return $this->dbh->lastInsertId();
    }

    public function rowCount()
    {
        return $this->stmt->rowCount();
    }

    public function bindValue($param, $value, $type = null)
    {
        if (is_null($type)) {
            switch (true) {
                case is_integer($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
                    break;
            }
        }

        $this->stmt->bindValue($param, $value, $type);
    }
}
