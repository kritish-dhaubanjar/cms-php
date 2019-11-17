<?php
class Posts extends Controller
{
    private $post;

    public function __construct()
    {
        $this->post = $this->model('post');
    }

    public function page($page){
        header("Content-Type: application/json");
        header("Access-Control-Allow-Origin: *");
        echo json_encode($this->post->paginate($page));
        return;
    }

    public function index()
    {
        header("Content-Type: application/json");
        header("Access-Control-Allow-Origin: *");
        echo json_encode($this->post->fetchAll());
        return;
    }

    public function store()
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json");

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

            if (empty($data["title_error"]) && empty($data["content_error"])) {
                echo json_encode($this->post->store($data, $this->model('photo')));
                return;
            } else {
                echo json_encode($data);
                return;
            }
        } else {
            return redirect('/');
        }
    }

    public function show($id)
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json");
        echo json_encode($this->post->fetch($id));
        return;
    }


    public function update($id)
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json");

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

            if (empty($data["title_error"]) && empty($data["content_error"])) {
                echo json_encode($this->post->update($id, $data, $this->model('photo')));
                return;
            } else {
                echo json_encode($data);
                return;
            }
        } else {
            return redirect('/');
        }
    }

    public function destroy($id)
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json");
        if ($_SERVER["REQUEST_METHOD"] == 'POST' && isAuth()) {
            echo json_encode($this->post->destroy($id, $this->model('photo')));
            return;
        } else {
            return redirect('/');
        }
    }
}
