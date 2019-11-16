<?php
class Feeds extends Controller
{
    private $feed;

    public function __construct()
    {
        $this->feed = $this->model('feed');
    }

    public function index()
    {
        header("Content-Type: application/json");
        header("Access-Control-Allow-Origin: *");
        echo json_encode($this->feed->fetchAll());
    }

    public function store()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isAuth()) {

            // $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = [
                "title" => trim($_POST["title"]),
                "content" => trim($_POST["content"]),
                "title_error" => false,
                "content_error" => false,
            ];

            if (empty($data["title"]))
                $data["title_error"] = true;

            if (empty($data["content"]))
                $data["content_error"] = true;

            header("Access-Control-Allow-Origin: *");
            header("Content-Type: application/json");

            if (empty($data["title_error"]) && empty($data["content_error"])) {
                echo json_encode($this->feed->store($data, $this->model('photo')));
            } else {
                echo json_encode($data);
            }
        } else {
            redirect("/");
        }
    }

    public function show($id)
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json");
        echo json_encode($this->feed->fetch($id));
    }


    public function update($id)
    {
        if ($_SERVER["REQUEST_METHOD"] == 'POST' && isAuth()) {
            // $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = [
                "title" => trim($_POST["title"]),
                "content" => trim($_POST["content"]),
                "items" => $_POST["items"],
                "title_error" => false,
                "content_error" => false,
            ];


            if (empty($data["title"]))
                $data["title_error"] = true;

            if (empty($data["content"]))
                $data["content_error"] = true;

            header("Access-Control-Allow-Origin: *");
            header("Content-Type: application/json");

            if (empty($data["title_error"]) && empty($data["content_error"])) {
                echo json_encode($this->feed->update($id, $data, $this->model('photo')));
            } else {
                echo json_encode($data);
            }
        } else {
            redirect("/");
        }
    }

    public function destroy($id)
    {
        if ($_SERVER["REQUEST_METHOD"] == 'POST' && isAuth()) {
            header("Access-Control-Allow-Origin: *");
            header("Content-Type: application/json");
            echo json_encode($this->feed->destroy($id, $this->model('photo')));
        } else {
            redirect("/");
        }
    }
}
