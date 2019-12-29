<?php

class Jobs extends Controller
{
    private $job;

    public function __construct()
    {
        $this->job = $this->model('job');
        header('Content-Type: application/json');
        header("Access-Control-Allow-Origin: *");
    }

    public function page($page)
    {
        echo json_encode($this->job->paginate($page));
        return;
    }

    public function index()
    {
        echo json_encode($this->job->fetchAll());
        return;
    }

    public function store()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isAuth()) {

            // $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = [
                "title" => trim($_POST["title"]),
                "lastDate" => trim($_POST["lastDate"]),
                "offeredSalary" => trim($_POST["offeredSalary"]),
                "careerLevel" => trim($_POST["careerLevel"]),
                "location" => trim($_POST["location"]),
                "industry" => trim($_POST["industry"]),
                "experience" => trim($_POST["experience"]),
                "content" => trim($_POST["content"]),

                "title_error" => false,
                "lastDate_error" => false,
                "offeredSalary_error" => false,
                "careerLevel_error" => false,
                "location_error" => false,
                "industry_error" => false,
                "experience_error" => false,
                "content_error" => false,
            ];

            if (empty($data["title"]))
                $data["title_error"] = true;

            if (empty($data["lastDate"]))
                $data["lastDate_error"] = true;

            if (empty($data["offeredSalary"]))
                $data["offeredSalary_error"] = true;

            if (empty($data["careerLevel"]))
                $data["careerLevel_error"] = true;

            if (empty($data["location"]))
                $data["location_error"] = true;

            if (empty($data["industry"]))
                $data["industry_error"] = true;

            if (empty($data["experience"]))
                $data["experience_error"] = true;

            if (empty($data["content"]))
                $data["content_error"] = true;


            if (empty($data["title_error"]) && empty($data["lastDate_error"]) && empty($data["offeredSalary_error"]) && empty($data["careerLevel_error"]) && empty($data["location_error"]) && empty($data["industry_error"]) && empty($data["experience_error"]) && empty($data["content_error"])) {
                echo json_encode($this->job->store($data, $this->model('photo')));
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
        echo json_encode($this->job->fetch($id));
        return;
    }


    public function update($id)
    {
        if ($_SERVER["REQUEST_METHOD"] == 'POST' && isAuth()) {
            // $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = [
                "title" => trim($_POST["title"]),
                "lastDate" => trim($_POST["lastDate"]),
                "offeredSalary" => trim($_POST["offeredSalary"]),
                "careerLevel" => trim($_POST["careerLevel"]),
                "location" => trim($_POST["location"]),
                "industry" => trim($_POST["industry"]),
                "experience" => trim($_POST["experience"]),
                "content" => trim($_POST["content"]),
                "items" => $_POST["items"],
                "title_error" => false,
                "date_error" => false,
                "offeredSalary_error" => false,
                "careerLevel_error" => false,
                "location_error" => false,
                "industry_error" => false,
                "experience_error" => false,
                "content_error" => false,
            ];


            if (empty($data["title"]))
                $data["title_error"] = true;

            if (empty($data["lastDate"]))
                $data["lastDate_error"] = true;

            if (empty($data["offeredSalary"]))
                $data["offeredSalary_error"] = true;

            if (empty($data["careerLevel"]))
                $data["careerLevel_error"] = true;

            if (empty($data["location"]))
                $data["location_error"] = true;

            if (empty($data["industry"]))
                $data["industry_error"] = true;

            if (empty($data["experience"]))
                $data["experience_error"] = true;

            if (empty($data["content"]))
                $data["content_error"] = true;

            if (empty($data["title_error"]) && empty($data["lastDate_error"]) && empty($data["offeredSalary_error"]) && empty($data["careerLevel_error"]) && empty($data["location_error"]) && empty($data["industry_error"]) && empty($data["experience_error"]) && empty($data["content_error"])) {
                echo json_encode($this->job->update($id, $data, $this->model('photo')));
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
            echo json_encode($this->job->destroy($id, $this->model('photo')));
            return;
        } else {
            return redirect('/');
        }
    }

    public function filter()
    {
        if ($_SERVER["REQUEST_METHOD"] == 'POST') {
            header("Access-Control-Allow-Origin: *");
            header("Content-Type: application/json");
            $data = [
                'page' => trim($_POST['page']),
                'keyword' => trim($_POST['keyword']),
                'location' => trim($_POST['location']),
                'industry' => trim($_POST['industry']),
                'careerLevel' => trim($_POST['careerLevel']),
                'offeredSalary' => trim($_POST['offeredSalary']),
                'experience' => trim($_POST['experience'])
            ];

            echo json_encode($this->job->filter($data));
            return;
        }
    }
}
