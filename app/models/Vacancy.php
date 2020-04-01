<?php

class Vacancy extends Model
{

    public function __construct()
    {
        parent::__construct(__CLASS__);
    }

    public function store($data)
    {
        $this->db->query("INSERT INTO vacancys(title, content) VALUES(:title, :content)");
        $this->db->bindValue(':title', $data["title"]);
        $this->db->bindValue(':content', $data["content"]);
        $this->db->execute();
        $rowCount = $this->db->rowCount();
        return $rowCount > 0 ? array('status' => 200) : array('status' => 500);
    }

    public function update($id, $data)
    {
        $this->db->query("UPDATE vacancys SET title=:title, content=:content, complete=:complete WHERE id=:id");
        $this->db->bindValue(':title', $data["title"]);
        $this->db->bindValue(':content', $data["content"]);
        $this->db->bindValue(':complete', $data["complete"]);
        $this->db->bindValue(':id', $id);
        $this->db->execute();
        return array('status' => 200);
    }

    public function destroy($id)
    {
        $this->db->query("DELETE FROM vacancys WHERE id=:id");
        $this->db->bindValue(':id', $id);
        $this->db->execute();

        $rowCount = $this->db->rowCount();

        return $rowCount > 0 ? array('status' => 200) : array('status' => 404);
    }
}
