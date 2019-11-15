<?php
class Post
{

    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function fetch($id)
    {
        $this->db->query("SELECT * FROM posts WHERE posts.id=:id");
        $this->db->bindValue(':id', $id);
        $post = $this->db->fetch();

        $rowCount = $this->db->rowCount();

        $data = [];

        $this->db->query("SELECT * FROM photos WHERE _id=:id");
        $this->db->bindValue(":id", $post->id);
        $post->photos = $this->db->fetchAll();

        array_push($data, $post);

        return $rowCount  > 0 ? $post : [];
    }

    public function fetchAll()
    {
        $this->db->query("SELECT * FROM posts");
        $posts = $this->db->fetchAll();
        $rowCount = $this->db->rowCount();

        $data = [];

        foreach ($posts as $post) {
            $this->db->query("SELECT * FROM photos WHERE _id=:id");
            $this->db->bindValue(":id", $post->id);
            $post->photos = $this->db->fetchAll();
            array_push($data, $post);
        }

        return $rowCount > 0 ? $data : [];
    }

    public function destroy($id, $photoModel)
    {
        $this->db->query("DELETE FROM posts WHERE id=:id");
        $this->db->bindValue(':id', $id);
        $this->db->execute();

        $rowCount = $this->db->rowCount();

        $photoModel->destroy('Post', $id);

        return $rowCount > 0 ? array('status' => 200) : array('status' => 404);
    }

    public function store($data, $photoModel)
    {
        $this->db->query("INSERT INTO posts(title, content) VALUES(:title, :content)");
        $this->db->bindValue(':title', $data["title"]);
        $this->db->bindValue(':content', $data["content"]);
        $this->db->execute();
        $id = $this->db->lastInsertId();
        $photoModel->store('Post', $id, null);
        return $this->db->rowCount() > 0 ? array('status' => 200) : array('status' => 500);
    }

    public function update($id, $data, $photoModel)
    {

        $this->db->query("UPDATE posts SET title=:title, content=:content WHERE id=:id");
        $this->db->bindValue(':title', $data["title"]);
        $this->db->bindValue(':content', $data["content"]);
        $this->db->bindValue(':id', $id);
        $this->db->execute();

        $this->db->query("SELECT url FROM photos WHERE _id=:_id AND class=:class");
        $this->db->bindValue(":_id", $id);
        $this->db->bindValue(":class", 'Post');
        $photos = $this->db->fetchAll();

        $temp = [];

        foreach ($photos as $photo) {
            array_push($temp, $photo->url);
        }

        $images = array_diff($temp, $data["items"]);

        foreach ($images as $image) {
            unlink(HOME . "/images/" . $image);
            $this->db->query("DELETE FROM photos WHERE class=:class AND url=:url AND _id=:_id");
            $this->db->bindValue(':_id', $id);
            $this->db->bindValue(":class", 'Post');
            $this->db->bindValue(":url", $image);
            $this->db->execute();
        }

        $photoModel->store('Post', $id, null);
        return $this->db->rowCount() > 0 ? array('status' => 200) : array('status' => 500);
    }
}
