<?php
class Pages extends Controller
{
    private $user;
    public function __construct()
    {
        $this->user = $this->model('user');
    }

    public function index()
    {
        if (!isAuth()) {
            $credentials = array(
                'email' => '',
                'password' => '',
                'email_err' => '',
                'password_err' => ''
            );
            return $this->view('Home', $credentials);
        } else {
            return $this->view('Home');
        }
    }

    public function login()
    {
        if ($_SERVER["REQUEST_METHOD"] == 'POST' && !isAuth()) {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $credentials = array(
                'email' => $_POST['email'],
                'password' => $_POST['password'],
                'email_err' => '',
                'password_err' => ''
            );

            if (empty($credentials['email'])) {
                $credentials['email_err'] = 'Please provide an email.';
            }
            if (empty($credentials['password'])) {
                $credentials['password_err'] = 'Please provide a password.';
            }

            if (empty($credentials['email_err']) && empty($credentials['password_err'])) {
                if (!$this->user->findByEmail($credentials['email'])) {
                    $credentials['email_err'] = 'Incorrect Email';
                    return $this->view('Home', $credentials);
                } else {
                    if ($this->user->login($credentials['email'], $credentials['password'])) {
                        return $this->view('Home');
                    } else {
                        $credentials['password_err'] = 'Incorrect Password';
                        return $this->view('Home', $credentials);
                    }
                }
            } else {
                return $this->view('Home', $credentials);
            }
        } else {
            return redirect('/');
        }
    }

    public function logout()
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json");
        if ($_SERVER["REQUEST_METHOD"] == 'POST' && isAuth()) {
            unset($_SESSION['id']);
            session_destroy();
            echo json_encode(array(
                "status" => 200
            ));
            return;
        } else {
            return redirect('/');
        }
    }

    public function changePassword()
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json");
        if ($_SERVER["REQUEST_METHOD"] == 'POST' && isAuth()) {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $credentials = array(
                'password' => $_POST['password'],
                'confirm_password' => $_POST['confirm_password'],
                'password_err' => '',
                'confirm_password_err' => ''
            );


            if (empty($credentials['password'])) {
                $credentials['password_err'] = 'Please provide a new password.';
            }
            if (empty($credentials['confirm_password'])) {
                $credentials['confirm_password_err'] = 'Please confirm new password.';
            }

            if (empty($credentials['password_err']) && empty($credentials['confirm_password_err'])) {

                if ($credentials['password'] == $credentials['confirm_password']) {
                    if ($this->user->changePassword($credentials['password'])) {
                        echo json_encode(array(
                            'status' => 200
                        ));
                        return;
                    }
                }
            } else {
                echo json_encode($credentials);
                return;
            }
        } else {
            return redirect('/');
        }
    }
}
