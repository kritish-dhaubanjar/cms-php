<?php
class Shares extends Controller
{
    private $share;

    public function __construct()
    {
        apiMiddleware();
        $this->share = $this->model('share');
        header('Content-Type: application/json');
        header("Access-Control-Allow-Origin: *");
    }

    public function page($page)
    {
        echo json_encode($this->share->paginate($page));
        return;
    }

    public function index()
    {
        echo json_encode($this->share->fetchAll());
        return;
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

            if (empty($data["title_error"]) && empty($data["content_error"])) {
                echo json_encode($this->share->store($data, $this->model('photo')));
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
        echo json_encode($this->share->fetch($id));
        return;
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

            if (empty($data["title_error"]) && empty($data["content_error"])) {
                echo json_encode($this->share->update($id, $data, $this->model('photo')));
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
        if ($_SERVER["REQUEST_METHOD"] == 'POST' && isAuth()) {
            echo json_encode($this->share->destroy($id, $this->model('photo')));
            return;
        } else {
            return redirect('/');
        }
    }
}
