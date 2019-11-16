<?php
class Page
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function counter()
    {
        $this->db->query("SELECT count(id) AS posts FROM posts");
        $posts = $this->db->fetch();

        $this->db->query("SELECT count(id) AS feeds FROM feeds");
        $feeds = $this->db->fetch();

        $this->db->query("SELECT count(id) AS projects FROM projects");
        $projects = $this->db->fetch();

        $this->db->query("SELECT count(id) AS vacancies FROM vacancys");
        $vacancies = $this->db->fetch();

        $this->db->query("SELECT count(id) AS photos FROM photos WHERE class=:class");
        $this->db->bindValue(':class', 'Cover');
        $covers = $this->db->fetch();

        $this->db->bindValue(':class', 'Photo');
        $photos = $this->db->fetch();

        $this->db->bindValue(':class', 'Carousel');
        $carousels = $this->db->fetch();

        $data = array(
            'posts' => $posts->posts,
            'feeds' => $feeds->feeds,
            'vacancies' => $vacancies->vacancies,
            'photos' => $photos->photos,
            'covers' => $covers->photos,
            'carousels' => $carousels->photos,
            'projects' => $projects->projects
        );


        header("Content-Type: application/json");
        echo json_encode($data);
    }
}
