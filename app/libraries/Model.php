<?php
class Model
{
    protected $db;
    protected $class;
    protected $database;

    public function __construct($class)
    {
        $this->db = new Database();
        $this->class = $class;
        $this->database = lcfirst($class) . 's';
    }

    public function fetch($id)
    {
        $this->db->query("SELECT * FROM " . $this->database . " WHERE id=:id ORDER BY id DESC");
        $this->db->bindValue(':id', $id);
        $row = $this->db->fetch();

        $rowCount = $this->db->rowCount();

        $data = [];

        $this->db->query("SELECT * FROM photos WHERE _id=:id");
        $this->db->bindValue(":id", $row->id);
        $row->photos = $this->db->fetchAll();

        array_push($data, $row);

        return $rowCount  > 0 ? $row : [];
    }

    public function fetchAll()
    {
        $this->db->query("SELECT * FROM " . $this->database);
        $rows = $this->db->fetchAll();
        $rowCount = $this->db->rowCount();

        $data = [];

        foreach ($rows as $row) {
            $this->db->query("SELECT * FROM photos WHERE _id=:id");
            $this->db->bindValue(":id", $row->id);
            $row->photos = $this->db->fetchAll();
            array_push($data, $row);
        }

        return $rowCount > 0 ? $data : [];
    }

    public function destroy($id, $photoModel)
    {
        $this->db->query("DELETE FROM " . $this->database . " WHERE id=:id");
        $this->db->bindValue(':id', $id);
        $this->db->execute();

        $rowCount = $this->db->rowCount();

        $photoModel->destroy($this->class, $id);

        return $rowCount > 0 ? array('status' => 200) : array('status' => 404);
    }

    public function store($data, $photoModel)
    {
        $this->db->query("INSERT INTO " . $this->database . "(title, content) VALUES(:title, :content)");
        $this->db->bindValue(':title', $data["title"]);
        $this->db->bindValue(':content', $data["content"]);
        $this->db->execute();
        $id = $this->db->lastInsertId();
        $photoModel->store($this->class, $id, null);
        return $this->db->rowCount() > 0 ? array('status' => 200) : array('status' => 500);
    }

    public function update($id, $data, $photoModel)
    {
        $this->db->query("UPDATE " . $this->database . " SET title=:title, content=:content WHERE id=:id");
        $this->db->bindValue(':title', $data["title"]);
        $this->db->bindValue(':content', $data["content"]);
        $this->db->bindValue(':id', $id);
        $this->db->execute();
        $rowCount = $this->db->rowCount();

        $rowCount += $photoModel->destroy($this->class, $id, $data["items"]);
        $rowCount += $photoModel->store($this->class, $id, null);
        return $rowCount > 0 ? array('status' => 200) : array('status' => 500);
    }
}
