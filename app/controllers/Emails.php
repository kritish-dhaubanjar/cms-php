<?php

class Emails extends Controller
{

    private $email;

    public function __construct()
    {
        apiMiddleware();
        header('Content-Type: application/json');
        header("Access-Control-Allow-Origin: *");
        $this->email = $this->model('email');
    }

    public function index()
    {
        // if (isAuth()) {
        echo json_encode($this->email->fetchAll());
        return;
        // }
    }

    public function store()
    {
        if ($_SERVER["REQUEST_METHOD"] == 'POST') {

            $data = [
                'name' => trim($_POST["name"]),
                'email' => trim($_POST["email"]),
                'subject' => trim($_POST["subject"]),
                'message' => trim($_POST["message"]),
                'name_error' => false,
                'email_error' => false,
                'subject_error' => false,
                'message_error' => false,
            ];

            if (empty($data['name']))
                $data['name_error'] = true;
            if (empty($data['email']))
                $data['email_error'] = true;
            if (empty($data['subject']))
                $data['subject_error'] = true;
            if (empty($data['message']))
                $data['message_error'] = true;

            if (empty($data['name_error']) && empty($data['email_error']) && empty($data['subject_error']) && empty($data['message_error'])) {

                if (mail(HOSTMASTER, $data['subject'], $data['message'], "FROM:" . $data["email"])) {
                    echo json_encode($this->email->store($data));
                    return;
                }
            } else {
                return redirect('/');
            }
        }
    }
}
