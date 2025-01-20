<?php
class Photo
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
        //parent::__construct(__CLASS__);
    }

    public function paginate($page)
    {
        $limit = 12;

        $this->db->query("SELECT count(id) AS count FROM photos WHERE class=:class");
        $this->db->bindValue(':class', 'Photo');
        $total = $this->db->fetch();

        $pages = ceil($total->count / $limit);

        $this->db->query("SELECT * FROM photos WHERE class=:class ORDER BY id DESC LIMIT :offset, :limit");
        $this->db->bindValue(':class', 'Photo');
        $this->db->bindValue(':limit', $limit);
        $this->db->bindValue(':offset', ($page - 1) * $limit);
        $temp = $this->db->fetchAll();

        $data["data"] = $temp;
        $data["meta"]["total_pages"] = $pages;
        $data["meta"]["prev_page"] = $page == 1 ? null : $page - 1;
        $data["meta"]["current_page"] = (int) $page;
        $data["meta"]["next_page"] = $page == $pages ? null : $page + 1;
        return $data;
    }

    // Gallery, Cover, Carousel
    public function fetchAll($class)
    {
        $this->db->query("SELECT * FROM photos WHERE class=:class ORDER BY id DESC");
        $this->db->bindValue(':class', $class);
        $rows = $this->db->fetchAll();

        $rowCount = $this->db->rowCount();
        return $rowCount > 0 ? $rows : [];
    }

    // Gallery, Cover, Carousel
    public function fetch($id, $class)
    {
        $this->db->query("SELECT * FROM photos WHERE id=:id AND class=:class");
        $this->db->bindValue(':id', $id);
        $this->db->bindValue(':class', $class);
        $row = $this->db->fetch();

        $rowCount = $this->db->rowCount();
        return $rowCount  > 0 ? $row : [];
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

    public function update($id, $data)
    {
        $this->db->query("SELECT * FROM photos WHERE id=:id");
        $this->db->bindValue(':id', $id);
        $photo = $this->db->fetch();

        $count = count($_FILES["images"]["name"]);
        if ($count > 0) {
            unlink(HOME . "/images/" . $photo->url);
            $name = getFilename($_FILES["images"]["name"]);
            $ext = getExtension($_FILES["images"]["name"]);
            $filename = $name . "_" . time() . "." . $ext;
            $tmp = $_FILES["images"]["tmp_name"];
            $destination =  HOME . "/images/" . $filename;
            if (move_uploaded_file($tmp, $destination)) {
                $this->db->query("UPDATE photos SET url=:url WHERE id=:id");
                $this->db->bindValue(':id', $id);
                $this->db->bindValue(':url', $filename);
                $this->db->execute();
            }
        }

        $this->db->query("UPDATE photos SET title=:title WHERE id=:id");
        $this->db->bindValue(':id', $id);
        $this->db->bindValue(':title', $data["title"]);
        $this->db->execute();
    }

    // Gallery, Cover, Carousel

    public function _destroy($id)
    {
        $this->db->query("SELECT * FROM photos WHERE id=:id");
        $this->db->bindValue(':id', $id);
        $photo = $this->db->fetch();

        unlink(HOME . "/images/" . $photo->url);
        $this->db->query("DELETE FROM photos WHERE url=:url AND id=:id");
        $this->db->bindValue(':id', $id);
        $this->db->bindValue(":url", $photo->url);
        $this->db->execute();
        return $this->db->rowCount();
    }

    // Gallery, Cover, Carousel

    public function destroy($class = null, $_id = null, $filter = null)
    {
        $this->db->query("SELECT * FROM photos WHERE _id=:_id AND class=:class");
        $this->db->bindValue(':_id', $_id);
        $this->db->bindValue(':class', $class);
        $photos = $this->db->fetchAll();

        if (!is_null($filter)) {
            //FILTER AND DESTROY
            $temp = [];

            foreach ($photos as $photo) {
                array_push($temp, $photo->url);
            }

            $images = array_diff($temp, $filter);

            foreach ($images as $image) {
                unlink(HOME . "/images/" . $image);
                $this->db->query("DELETE FROM photos WHERE class=:class AND url=:url AND _id=:_id");
                $this->db->bindValue(':_id', $_id);
                $this->db->bindValue(":class", $class);
                $this->db->bindValue(":url", $image);
                $this->db->execute();
            }
        } else {
            // NO FILTER
            foreach ($photos as $photo) {
                unlink(HOME . "/images/" . $photo->url);
            }

            $this->db->query("DELETE FROM photos WHERE class=:class AND _id=:_id");
            $this->db->bindValue(':class', $class);
            $this->db->bindValue(':_id', $_id);
            $this->db->execute();
        }
        return $this->db->rowCount();
    }
}
