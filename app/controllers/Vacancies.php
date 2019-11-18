<?php
class Vacancies extends Controller
{
    private $vacancy;

    public function __construct()
    {
        header("Content-Type: application/json");
        header("Access-Control-Allow-Origin: *");
        $this->vacancy = $this->model('vacancy');
    }

    public function page($page)
    {
        echo json_encode($this->vacancy->paginate($page));
        return;
    }

    public function index()
    {
        echo json_encode($this->vacancy->fetchAll());
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
                echo json_encode($this->vacancy->store($data));
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
        echo json_encode($this->vacancy->fetch($id));
        return;
    }


    public function update($id)
    {
        if ($_SERVER["REQUEST_METHOD"] == 'POST' && isAuth()) {
            // $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = [
                "title" => trim($_POST["title"]),
                "content" => trim($_POST["content"]),
                "complete" => trim($_POST["complete"]),
                "items" => $_POST["items"],
                "title_error" => false,
                "content_error" => false,
            ];


            if (empty($data["title"]))
                $data["title_error"] = true;

            if (empty($data["content"]))
                $data["content_error"] = true;

            if (empty($data["title_error"]) && empty($data["content_error"])) {
                echo json_encode($this->vacancy->update($id, $data));
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
            echo json_encode($this->vacancy->destroy($id));
            return;
        } else {
            return redirect('/');
        }
    }
}
