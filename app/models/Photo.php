<?php
class Photo
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function store($class = null, $_id = null, $title = null)
    {
        $count = count($_FILES["images"]["name"]);
        if ($count > 0) {
            for ($i = 0; $i < $count; $i++) {
                $name = getFilename($_FILES["images"]["name"][$i]);
                $ext = getExtension($_FILES["images"]["name"][$i]);
                $filename = $name . "_" . time() . "." . $ext;
                $tmp = $_FILES["images"]["tmp_name"][$i];
                $destination =  HOME . "/images/" . $filename;
                if (move_uploaded_file($tmp, $destination)) {
                    $this->db->query("INSERT INTO photos(title, url, _id, class) VALUES(:title, :url, :_id, :class)");
                    $this->db->bindValue(':title', $title);
                    $this->db->bindValue(':url', $filename);
                    $this->db->bindValue(':_id', $_id);
                    $this->db->bindValue(':class', $class);
                    $this->db->execute();
                }
            }
        }
    }

    public function destroy($class = null, $_id = null)
    {
        $this->db->query("SELECT * FROM photos WHERE class=:class AND _id=:_id");
        $this->db->bindValue(':class', $class);
        $this->db->bindValue(':_id', $_id);
        $photos = $this->db->fetchAll();

        foreach ($photos as $photo) {
            unlink(HOME . "/images/" . $photo->url);
        }

        $this->db->query("DELETE FROM photos WHERE class=:class AND _id=:_id");
        $this->db->bindValue(':class', $class);
        $this->db->bindValue(':_id', $_id);
        $this->db->execute();
    }
}
